import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../core/session/session_controller.dart';
import 'evidence_detail_page.dart';
import 'evidence_form_sheet.dart';
import 'evidence_repository.dart';

class EvidencePage extends StatefulWidget {
  const EvidencePage({super.key});

  @override
  State<EvidencePage> createState() => _EvidencePageState();
}

class _EvidencePageState extends State<EvidencePage> {
  Future<Map<String, dynamic>>? _future;
  List<dynamic> _evidences = [];
  List<dynamic> _kriterias = [];

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    _load();
  }

  void _load() {
    final token = context.read<SessionController>().token;
    if (token == null) return;
    setState(() {
      _future = EvidenceRepository(token).index();
    });
  }

  Future<void> _openForm({Map<String, dynamic>? evidence}) async {
    final token = context.read<SessionController>().token;
    if (token == null) return;

    final changed = await showModalBottomSheet<bool>(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => EvidenceFormSheet(
        token: token,
        kriterias: _kriterias,
        evidence: evidence,
      ),
    );

    if (changed == true) {
      _load();
      if (mounted) setState(() {});
    }
  }

  @override
  Widget build(BuildContext context) {
    return RefreshIndicator(
      onRefresh: () async {
        _load();
        await _future;
      },
      child: FutureBuilder<Map<String, dynamic>>(
        future: _future,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }

          if (snapshot.hasError) {
            return ListView(
              children: [
                const SizedBox(height: 120),
                Center(child: Text(snapshot.error.toString())),
              ],
            );
          }

          if (snapshot.hasData) {
            _evidences = List<dynamic>.from(snapshot.data!['evidences'] ?? []);
            _kriterias = List<dynamic>.from(snapshot.data!['kriterias'] ?? []);
          }

          return ListView(
            padding: const EdgeInsets.all(16),
            children: [
              _SummaryCard(count: _evidences.length),
              const SizedBox(height: 16),
              ..._evidences.map(
                (item) => Card(
                  margin: const EdgeInsets.only(bottom: 12),
                  child: ListTile(
                    onTap: () async {
                      final token = context.read<SessionController>().token;
                      if (token == null) return;
                      final detail = await EvidenceRepository(token).show(item['id'] as int);
                      if (!context.mounted) return;

                      await Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (_) => EvidenceDetailPage(
                            evidence: detail['evidence'] as Map<String, dynamic>,
                            onEdit: () {
                              Navigator.pop(context);
                              _openForm(evidence: detail['evidence'] as Map<String, dynamic>);
                            },
                            onDelete: () async {
                              await EvidenceRepository(token).delete(item['id'] as int);
                              _load();
                            },
                          ),
                        ),
                      );
                    },
                    leading: CircleAvatar(
                      backgroundColor: const Color(0xFFE0F2FE),
                      child: Icon(
                        item['status'] == 'approved'
                            ? Icons.check_circle_outline
                            : item['status'] == 'rejected'
                                ? Icons.cancel_outlined
                                : Icons.schedule_outlined,
                        color: const Color(0xFF0284C7),
                      ),
                    ),
                    title: Text(item['subject']?.toString() ?? 'Evidence'),
                    subtitle: Text(
                      '${item['kriteria'] ?? '-'}\n${item['tanggal'] ?? '-'}',
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    isThreeLine: true,
                    trailing: const Icon(Icons.chevron_right),
                  ),
                ),
              ),
              const SizedBox(height: 120),
            ],
          );
        },
      ),
    );
  }
}

class _SummaryCard extends StatelessWidget {
  const _SummaryCard({required this.count});

  final int count;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        gradient: const LinearGradient(colors: [Color(0xFF0EA5E9), Color(0xFF2563EB)]),
        borderRadius: BorderRadius.circular(24),
      ),
      child: Row(
        children: [
          const Icon(Icons.folder_copy_outlined, color: Colors.white),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('Evidence', style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.w700)),
                const SizedBox(height: 4),
                Text('$count file tersimpan', style: TextStyle(color: Colors.white.withOpacity(.88))),
              ],
            ),
          ),
          FilledButton(
            onPressed: () async {
              final token = context.read<SessionController>().token;
              if (token == null) return;
              final data = await EvidenceRepository(token).index();
              final kriterias = List<dynamic>.from(data['kriterias'] ?? []);
              if (!context.mounted) return;
              await showModalBottomSheet(
                context: context,
                isScrollControlled: true,
                backgroundColor: Colors.transparent,
                builder: (context) => EvidenceFormSheet(
                  token: token,
                  kriterias: kriterias,
                ),
              );
            },
            style: FilledButton.styleFrom(backgroundColor: Colors.white, foregroundColor: const Color(0xFF0EA5E9)),
            child: const Text('Tambah'),
          ),
        ],
      ),
    );
  }
}
