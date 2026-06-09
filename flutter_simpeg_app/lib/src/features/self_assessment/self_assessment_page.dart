import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../core/session/session_controller.dart';
import 'self_assessment_repository.dart';
import 'self_assessment_review_page.dart';

class SelfAssessmentPage extends StatefulWidget {
  const SelfAssessmentPage({super.key});

  @override
  State<SelfAssessmentPage> createState() => _SelfAssessmentPageState();
}

class _SelfAssessmentPageState extends State<SelfAssessmentPage> {
  Future<Map<String, dynamic>>? _future;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    _reload();
  }

  void _reload() {
    final token = context.read<SessionController>().token;
    if (token == null) return;
    setState(() {
      _future = SelfAssessmentRepository(token).index();
    });
  }

  @override
  Widget build(BuildContext context) {
    final token = context.read<SessionController>().token;

    return FutureBuilder<Map<String, dynamic>>(
      future: _future,
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const Center(child: CircularProgressIndicator());
        }

        if (snapshot.hasError) {
          return Center(child: Text(snapshot.error.toString()));
        }

        final data = snapshot.data ?? {};
        final assessments = List<dynamic>.from(data['assessments'] ?? []);
        final periods = List<dynamic>.from(data['periods'] ?? []);

        return RefreshIndicator(
          onRefresh: () async {
            _reload();
            await _future;
          },
          child: ListView(
            padding: const EdgeInsets.all(16),
            children: [
              Container(
                padding: const EdgeInsets.all(18),
                decoration: BoxDecoration(
                  gradient: const LinearGradient(colors: [Color(0xFF0EA5E9), Color(0xFF2563EB)]),
                  borderRadius: BorderRadius.circular(24),
                ),
                child: Row(
                  children: [
                    const Icon(Icons.fact_check_outlined, color: Colors.white),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Text(
                        '${assessments.length} self assessment tersimpan',
                        style: const TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.w700),
                      ),
                    ),
                    FilledButton(
                      onPressed: token == null
                          ? null
                          : () async {
                              final selected = await showModalBottomSheet<int?>(
                                context: context,
                                isScrollControlled: true,
                                builder: (context) {
                                  int? chosen;
                                  return StatefulBuilder(
                                    builder: (context, setState) => Padding(
                                      padding: const EdgeInsets.all(16),
                                      child: Column(
                                        mainAxisSize: MainAxisSize.min,
                                        children: [
                                          const Text('Pilih Periode', style: TextStyle(fontSize: 18, fontWeight: FontWeight.w800)),
                                          const SizedBox(height: 12),
                                          ...periods.map(
                                            (period) => RadioListTile<int>(
                                              value: period['id'] as int,
                                              groupValue: chosen,
                                              title: Text(period['name'].toString()),
                                              onChanged: (value) => setState(() => chosen = value),
                                            ),
                                          ),
                                          const SizedBox(height: 12),
                                          FilledButton(
                                            onPressed: () => Navigator.pop(context, chosen),
                                            child: const Text('Buat'),
                                          ),
                                        ],
                                      ),
                                    ),
                                  );
                                },
                              );

                              if (selected == null) return;
                              final response = await SelfAssessmentRepository(token).create(selected);
                              if (!context.mounted) return;
                              _reload();
                              await Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (_) => SelfAssessmentReviewPage(
                                    assessmentId: response['assessment']['id'] as int,
                                  ),
                                ),
                              );
                              _reload();
                            },
                      child: const Text('Buat'),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 16),
              ...assessments.map(
                (assessment) => Card(
                  margin: const EdgeInsets.only(bottom: 12),
                  child: ListTile(
                    onTap: () async {
                      await Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (_) => SelfAssessmentReviewPage(
                            assessmentId: assessment['id'] as int,
                          ),
                        ),
                      );
                      _reload();
                    },
                    leading: const CircleAvatar(
                      backgroundColor: Color(0xFFE0F2FE),
                      child: Icon(Icons.library_books_outlined, color: Color(0xFF0284C7)),
                    ),
                    title: Text(assessment['period']?.toString() ?? '-'),
                    subtitle: Text(
                      assessment['status']?.toString().toUpperCase() ?? '-',
                      style: const TextStyle(fontWeight: FontWeight.w700),
                    ),
                    trailing: const Icon(Icons.chevron_right),
                  ),
                ),
              ),
              const SizedBox(height: 100),
            ],
          ),
        );
      },
    );
  }
}
