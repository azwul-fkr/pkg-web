import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../core/session/session_controller.dart';
import 'dashboard_repository.dart';

class DashboardPage extends StatefulWidget {
  const DashboardPage({super.key});

  @override
  State<DashboardPage> createState() => _DashboardPageState();
}

class _DashboardPageState extends State<DashboardPage> {
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
      _future = DashboardRepository(token).fetch();
    });
  }

  @override
  Widget build(BuildContext context) {
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
        final summary = Map<String, dynamic>.from(data['summary'] ?? {});
        final reflection = data['reflection'] as Map<String, dynamic>?;
        final evaluation = data['evaluation'] as Map<String, dynamic>?;
        final recommendationEngine = Map<String, dynamic>.from(data['recommendation_engine'] ?? {});
        final items = List<dynamic>.from(recommendationEngine['items'] ?? []);
        final bestWorst = Map<String, dynamic>.from(data['best_worst'] ?? {});
        final trend = Map<String, dynamic>.from(data['trend'] ?? {});
        final trendLabels = List<dynamic>.from(trend['labels'] ?? []);
        final trendScores = List<dynamic>.from(trend['scores'] ?? []);

        return RefreshIndicator(
          onRefresh: () async {
            _reload();
            await _future;
          },
          child: ListView(
            padding: const EdgeInsets.all(16),
            children: [
              _HeroCard(
                name: data['guru']?['name']?.toString() ?? '-',
                period: data['period']?['name']?.toString() ?? '-',
              ),
              const SizedBox(height: 16),
              Row(
                children: [
                  Expanded(child: _MetricCard(label: 'Evidence', value: '${summary['total_evidence'] ?? 0}')),
                  const SizedBox(width: 12),
                  Expanded(child: _MetricCard(label: 'Approved', value: '${summary['approved_evidence'] ?? 0}', accent: const Color(0xFF059669))),
                ],
              ),
              const SizedBox(height: 12),
              Row(
                children: [
                  Expanded(child: _MetricCard(label: 'Pending', value: '${summary['pending_evidence'] ?? 0}', accent: const Color(0xFFD97706))),
                  const SizedBox(width: 12),
                  Expanded(child: _MetricCard(label: 'Rejected', value: '${summary['rejected_evidence'] ?? 0}', accent: const Color(0xFFDC2626))),
                ],
              ),
              const SizedBox(height: 16),
              if (reflection != null)
                _SectionCard(
                  title: 'Refleksi Terbaru',
                  child: Text(
                    '${reflection['reflection'] ?? '-'}\n\nRencana: ${reflection['improvement_plan'] ?? '-'}',
                    style: const TextStyle(height: 1.6),
                  ),
                ),
              if (evaluation != null) ...[
                const SizedBox(height: 16),
                _SectionCard(
                  title: 'Hasil Penilaian',
                  child: Row(
                    children: [
                      Expanded(
                        child: Text(
                          '${summary['final_score'] ?? '-'}',
                          style: const TextStyle(fontSize: 34, fontWeight: FontWeight.w800),
                        ),
                      ),
                      Chip(label: Text(evaluation['status']?.toString().toUpperCase() ?? '-')),
                    ],
                  ),
                ),
              ],
              if (trendLabels.isNotEmpty) ...[
                const SizedBox(height: 16),
                _SectionCard(
                  title: 'Tren Skor',
                  child: Wrap(
                    spacing: 8,
                    runSpacing: 8,
                    children: List.generate(trendLabels.length, (index) {
                      final score = index < trendScores.length ? trendScores[index] : '-';
                      return Chip(
                        label: Text('${trendLabels[index]}: $score'),
                      );
                    }),
                  ),
                ),
              ],
              const SizedBox(height: 16),
              _SectionCard(
                title: 'Kompetensi Terbaik',
                child: Text(bestWorst['best']?['kompetensi']?.toString() ?? 'Belum ada data.'),
              ),
              const SizedBox(height: 12),
              _SectionCard(
                title: 'Kompetensi Terlemah',
                child: Text(bestWorst['worst']?['kompetensi']?.toString() ?? 'Belum ada data.'),
              ),
              if (items.isNotEmpty) ...[
                const SizedBox(height: 16),
                _SectionCard(
                  title: 'Prioritas Pengembangan',
                  child: Column(
                    children: items
                        .map(
                          (item) => Container(
                            width: double.infinity,
                            margin: const EdgeInsets.only(bottom: 12),
                            padding: const EdgeInsets.all(14),
                            decoration: BoxDecoration(
                              color: const Color(0xFFFFFBEB),
                              borderRadius: BorderRadius.circular(18),
                              border: Border.all(color: const Color(0xFFFDE68A)),
                            ),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(item['kriteria']?.toString() ?? '-', style: const TextStyle(fontWeight: FontWeight.w700)),
                                const SizedBox(height: 6),
                                Text(item['insight']?.toString() ?? '-', style: const TextStyle(height: 1.5)),
                                const SizedBox(height: 10),
                                Text('Skor: ${item['score']}', style: const TextStyle(fontWeight: FontWeight.w700)),
                              ],
                            ),
                          ),
                        )
                        .toList(),
                  ),
                ),
              ],
              const SizedBox(height: 80),
            ],
          ),
        );
      },
    );
  }
}

class _HeroCard extends StatelessWidget {
  const _HeroCard({required this.name, required this.period});

  final String name;
  final String period;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        gradient: const LinearGradient(colors: [Color(0xFF0EA5E9), Color(0xFF2563EB)]),
        borderRadius: BorderRadius.circular(24),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text('Dashboard Guru', style: TextStyle(color: Colors.white70, fontWeight: FontWeight.w700)),
          const SizedBox(height: 8),
          Text(
            name,
            style: const TextStyle(color: Colors.white, fontSize: 22, fontWeight: FontWeight.w800),
          ),
          const SizedBox(height: 6),
          Text(
            'Periode aktif: $period',
            style: TextStyle(color: Colors.white.withOpacity(.9)),
          ),
        ],
      ),
    );
  }
}

class _MetricCard extends StatelessWidget {
  const _MetricCard({
    required this.label,
    required this.value,
    this.accent = const Color(0xFF0EA5E9),
  });

  final String label;
  final String value;
  final Color accent;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(label, style: TextStyle(color: accent, fontWeight: FontWeight.w700)),
            const SizedBox(height: 8),
            Text(value, style: const TextStyle(fontSize: 26, fontWeight: FontWeight.w800)),
          ],
        ),
      ),
    );
  }
}

class _SectionCard extends StatelessWidget {
  const _SectionCard({
    required this.title,
    required this.child,
  });

  final String title;
  final Widget child;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(title, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w800)),
            const SizedBox(height: 12),
            child,
          ],
        ),
      ),
    );
  }
}
