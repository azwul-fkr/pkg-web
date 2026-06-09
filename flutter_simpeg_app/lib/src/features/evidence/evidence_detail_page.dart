import 'package:url_launcher/url_launcher.dart';
import 'package:flutter/material.dart';

class EvidenceDetailPage extends StatelessWidget {
  const EvidenceDetailPage({
    super.key,
    required this.evidence,
    required this.onEdit,
    required this.onDelete,
  });

  final Map<String, dynamic> evidence;
  final VoidCallback onEdit;
  final Future<void> Function() onDelete;

  @override
  Widget build(BuildContext context) {
    final status = (evidence['status'] ?? 'pending').toString();

    return Scaffold(
      appBar: AppBar(
        title: const Text('Detail Evidence'),
        actions: [
          IconButton(
            onPressed: onEdit,
            icon: const Icon(Icons.edit_outlined),
          ),
          IconButton(
            onPressed: () async {
              final confirm = await showDialog<bool>(
                context: context,
                builder: (context) => AlertDialog(
                  title: const Text('Hapus evidence?'),
                  content: const Text('Tindakan ini tidak bisa dibatalkan.'),
                  actions: [
                    TextButton(
                      onPressed: () => Navigator.pop(context, false),
                      child: const Text('Batal'),
                    ),
                    FilledButton(
                      onPressed: () => Navigator.pop(context, true),
                      child: const Text('Hapus'),
                    ),
                  ],
                ),
              );

              if (confirm == true) {
                await onDelete();
                if (context.mounted) Navigator.pop(context);
              }
            },
            icon: const Icon(Icons.delete_outline),
          ),
        ],
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          _Header(status: status),
          const SizedBox(height: 16),
          _InfoCard(
            title: 'Kriteria',
            value: evidence['kriteria']?.toString() ?? '-',
          ),
          _InfoCard(
            title: 'Kompetensi',
            value: evidence['sub_kriteria']?.toString() ?? '-',
          ),
          _InfoCard(
            title: 'Indikator',
            value: evidence['indikator']?.toString() ?? '-',
          ),
          _InfoCard(
            title: 'Mata Pelajaran',
            value: evidence['subject']?.toString() ?? '-',
          ),
          _InfoCard(
            title: 'Kelas',
            value: evidence['kelas']?.toString() ?? '-',
          ),
          _InfoCard(
            title: 'Tanggal',
            value: evidence['tanggal']?.toString() ?? '-',
          ),
          _InfoCard(
            title: 'Deskripsi',
            value: evidence['description']?.toString() ?? '-',
            multiline: true,
          ),
          _InfoCard(
            title: 'File',
            value: evidence['file_url']?.toString() ?? '-',
            trailing: TextButton(
              onPressed: evidence['file_url'] == null
                  ? null
                  : () async {
                      final uri = Uri.tryParse(evidence['file_url'].toString());
                      if (uri != null) {
                        await launchUrl(uri, mode: LaunchMode.externalApplication);
                      }
                    },
              child: const Text('Buka'),
            ),
          ),
        ],
      ),
    );
  }
}

class _Header extends StatelessWidget {
  const _Header({required this.status});

  final String status;

  @override
  Widget build(BuildContext context) {
    final color = switch (status) {
      'approved' => Colors.green,
      'rejected' => Colors.red,
      _ => Colors.amber,
    };

    return Container(
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFF0EA5E9), Color(0xFF2563EB)],
        ),
        borderRadius: BorderRadius.circular(24),
      ),
      child: Row(
        children: [
          const Icon(Icons.description_outlined, color: Colors.white),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'Evidence',
                  style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.w700),
                ),
                const SizedBox(height: 4),
                Text(
                  status.toUpperCase(),
                  style: TextStyle(
                    color: color.shade50,
                    fontSize: 12,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ],
            ),
          ),
          Chip(
            label: Text(
              status.toUpperCase(),
              style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w700),
            ),
            backgroundColor: color,
          ),
        ],
      ),
    );
  }
}

class _InfoCard extends StatelessWidget {
  const _InfoCard({
    required this.title,
    required this.value,
    this.trailing,
    this.multiline = false,
  });

  final String title;
  final String value;
  final Widget? trailing;
  final bool multiline;

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Row(
          crossAxisAlignment: multiline ? CrossAxisAlignment.start : CrossAxisAlignment.center,
          children: [
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    title,
                    style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: Color(0xFF64748B)),
                  ),
                  const SizedBox(height: 6),
                  Text(
                    value,
                    style: TextStyle(
                      fontSize: 14,
                      height: 1.6,
                      color: Colors.grey.shade800,
                    ),
                  ),
                ],
              ),
            ),
            if (trailing != null) trailing!,
          ],
        ),
      ),
    );
  }
}
