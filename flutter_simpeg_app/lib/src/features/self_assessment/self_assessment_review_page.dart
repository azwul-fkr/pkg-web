import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../core/session/session_controller.dart';
import 'self_assessment_repository.dart';

class SelfAssessmentReviewPage extends StatefulWidget {
  const SelfAssessmentReviewPage({
    super.key,
    required this.assessmentId,
  });

  final int assessmentId;

  @override
  State<SelfAssessmentReviewPage> createState() => _SelfAssessmentReviewPageState();
}

class _SelfAssessmentReviewPageState extends State<SelfAssessmentReviewPage> {
  Future<Map<String, dynamic>>? _future;
  final Map<int, int> _scores = {};
  final Map<int, TextEditingController> _comments = {};
  bool _busy = false;
  bool _initializedFromServer = false;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    final token = context.read<SessionController>().token;
    if (token != null && _future == null) {
      _future = SelfAssessmentRepository(token).show(widget.assessmentId);
    }
  }

  @override
  void dispose() {
    for (final controller in _comments.values) {
      controller.dispose();
    }
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final token = context.read<SessionController>().token;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Review Self Assessment'),
      ),
      body: FutureBuilder<Map<String, dynamic>>(
        future: _future,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }

          if (snapshot.hasError) {
            return Center(child: Text(snapshot.error.toString()));
          }

          final data = snapshot.data ?? {};
          final assessment = Map<String, dynamic>.from(data['assessment'] as Map);
          final kriterias = List<dynamic>.from(data['kriterias'] ?? []);
          final scores = List<dynamic>.from(data['scores'] ?? []);

          if (!_initializedFromServer) {
            for (final score in scores) {
              final id = int.tryParse(score['indikator_id'].toString());
              if (id == null) continue;
              _scores[id] = int.tryParse(score['nilai'].toString()) ?? 0;
              _comments.putIfAbsent(
                id,
                () => TextEditingController(text: score['comment']?.toString() ?? ''),
              );
            }
            _initializedFromServer = true;
          }

          return ListView(
            padding: const EdgeInsets.all(16),
            children: [
              Container(
                padding: const EdgeInsets.all(18),
                decoration: BoxDecoration(
                  gradient: const LinearGradient(colors: [Color(0xFF0EA5E9), Color(0xFF2563EB)]),
                  borderRadius: BorderRadius.circular(24),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      assessment['period']?.toString() ?? '-',
                      style: const TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.w700),
                    ),
                    const SizedBox(height: 6),
                    Text(
                      'Status: ${assessment['status']?.toString().toUpperCase() ?? '-'}',
                      style: TextStyle(color: Colors.white.withOpacity(.9)),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 16),
              ...kriterias.map(
                (kriteria) => Card(
                  margin: const EdgeInsets.only(bottom: 12),
                  child: ExpansionTile(
                    title: Text(kriteria['name'].toString(), style: const TextStyle(fontWeight: FontWeight.w700)),
                    subtitle: Text('Bobot: ${kriteria['bobot']}%'),
                    children: [
                      ...List<dynamic>.from(kriteria['sub_kriterias'] ?? []).map(
                        (sub) => Padding(
                          padding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
                          child: Card(
                            color: const Color(0xFFF8FAFC),
                            child: ExpansionTile(
                              title: Text(sub['name'].toString()),
                              subtitle: Text('Kode: ${sub['kode']} | Bobot: ${sub['bobot']}%'),
                              children: [
                                ...List<dynamic>.from(sub['indikators'] ?? []).map(
                                  (indikator) {
                                    final indikatorId = indikator['id'] as int;
                                    _comments.putIfAbsent(indikatorId, () => TextEditingController());

                                    final scoresMaster = List<dynamic>.from(indikator['indikator_scores'] ?? indikator['indikatorScores'] ?? []);

                                    return Padding(
                                      padding: const EdgeInsets.all(12),
                                      child: Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          Text(
                                            indikator['name'].toString(),
                                            style: const TextStyle(fontWeight: FontWeight.w700),
                                          ),
                                          const SizedBox(height: 8),
                                          Wrap(
                                            spacing: 8,
                                            runSpacing: 8,
                                            children: scoresMaster.map<Widget>((score) {
                                              final nilai = score['score'] as int;
                                              final selected = _scores[indikatorId] == nilai;
                                              return ChoiceChip(
                                                label: Text('${nilai} - ${score['description']}'),
                                                selected: selected,
                                                onSelected: (_) {
                                                  setState(() {
                                                    _scores[indikatorId] = nilai;
                                                  });
                                                },
                                              );
                                            }).toList(),
                                          ),
                                          const SizedBox(height: 10),
                                          TextField(
                                            controller: _comments[indikatorId],
                                            decoration: const InputDecoration(
                                              labelText: 'Komentar',
                                            ),
                                            maxLines: 2,
                                          ),
                                        ],
                                      ),
                                    );
                                  },
                                ),
                              ],
                            ),
                          ),
                        ),
                      ),
                      const SizedBox(height: 8),
                    ],
                  ),
                ),
              ),
            ],
          );
        },
      ),
      bottomNavigationBar: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Row(
            children: [
              Expanded(
                child: OutlinedButton(
                  onPressed: _busy || token == null
                      ? null
                      : () async {
                          setState(() => _busy = true);
                          try {
                            await SelfAssessmentRepository(token).saveDraftOrSubmit(
                              id: widget.assessmentId,
                              scores: _scores.map((key, value) => MapEntry(key.toString(), value)),
                              comments: _comments.map((key, value) => MapEntry(key.toString(), value.text)),
                              submit: false,
                            );
                            if (context.mounted) Navigator.pop(context, true);
                          } finally {
                            if (mounted) setState(() => _busy = false);
                          }
                        },
                  child: const Text('Simpan Draft'),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: FilledButton(
                  onPressed: _busy || token == null
                      ? null
                      : () async {
                          setState(() => _busy = true);
                          try {
                            await SelfAssessmentRepository(token).saveDraftOrSubmit(
                              id: widget.assessmentId,
                              scores: _scores.map((key, value) => MapEntry(key.toString(), value)),
                              comments: _comments.map((key, value) => MapEntry(key.toString(), value.text)),
                              submit: true,
                            );
                            if (context.mounted) Navigator.pop(context, true);
                          } finally {
                            if (mounted) setState(() => _busy = false);
                          }
                        },
                  child: const Text('Submit'),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
