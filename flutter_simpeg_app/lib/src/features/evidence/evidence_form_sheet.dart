import 'dart:io';

import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

import 'evidence_repository.dart';

class EvidenceFormSheet extends StatefulWidget {
  const EvidenceFormSheet({
    super.key,
    required this.token,
    required this.kriterias,
    this.evidence,
  });

  final String token;
  final List<dynamic> kriterias;
  final Map<String, dynamic>? evidence;

  @override
  State<EvidenceFormSheet> createState() => _EvidenceFormSheetState();
}

class _EvidenceFormSheetState extends State<EvidenceFormSheet> {
  final _formKey = GlobalKey<FormState>();
  final _subjectController = TextEditingController();
  final _kelasController = TextEditingController();
  final _descriptionController = TextEditingController();
  final _dateController = TextEditingController();

  late final EvidenceRepository _repository;

  int? _kriteriaId;
  int? _subKriteriaId;
  int? _indikatorId;
  PlatformFile? _file;
  bool _busy = false;

  @override
  void initState() {
    super.initState();
    _repository = EvidenceRepository(widget.token);

    final evidence = widget.evidence;
    if (evidence != null) {
      _subjectController.text = evidence['subject']?.toString() ?? '';
      _kelasController.text = evidence['kelas']?.toString() ?? '';
      _descriptionController.text = evidence['description']?.toString() ?? '';
      _dateController.text = evidence['tanggal']?.toString() ?? '';
      _kriteriaId = int.tryParse(evidence['kriteria_id']?.toString() ?? '');
      _subKriteriaId = int.tryParse(evidence['sub_kriteria_id']?.toString() ?? '');
      _indikatorId = int.tryParse(evidence['indikator_id']?.toString() ?? '');
    } else {
      _dateController.text = DateFormat('yyyy-MM-dd').format(DateTime.now());
    }
  }

  @override
  void dispose() {
    _subjectController.dispose();
    _kelasController.dispose();
    _descriptionController.dispose();
    _dateController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final kriterias = widget.kriterias.cast<Map<String, dynamic>>();
    final selectedKriteria = kriterias.where(
          (k) => k['id'] == _kriteriaId,
        ).toList();
    final subs = selectedKriteria.isEmpty ? <Map<String, dynamic>>[] : List<Map<String, dynamic>>.from(selectedKriteria.first['sub_kriterias'] ?? []);
    final selectedSub = subs.where((s) => s['id'] == _subKriteriaId).toList();
    final indikators = selectedSub.isEmpty ? <Map<String, dynamic>>[] : List<Map<String, dynamic>>.from(selectedSub.first['indikators'] ?? []);

    return SafeArea(
      child: Padding(
        padding: MediaQuery.of(context).viewInsets + const EdgeInsets.all(16),
        child: SingleChildScrollView(
          child: Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Form(
                key: _formKey,
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Text(
                      widget.evidence == null ? 'Upload Evidence' : 'Edit Evidence',
                      style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w800),
                    ),
                    const SizedBox(height: 16),
                    TextFormField(
                      controller: _subjectController,
                      decoration: const InputDecoration(labelText: 'Mata Pelajaran'),
                      validator: _required,
                    ),
                    const SizedBox(height: 12),
                    TextFormField(
                      controller: _kelasController,
                      decoration: const InputDecoration(labelText: 'Kelas'),
                      validator: _required,
                    ),
                    const SizedBox(height: 12),
                    TextFormField(
                      controller: _dateController,
                      readOnly: true,
                      decoration: const InputDecoration(labelText: 'Tanggal'),
                      onTap: () async {
                        final picked = await showDatePicker(
                          context: context,
                          firstDate: DateTime(2020),
                          lastDate: DateTime(2100),
                          initialDate: DateTime.tryParse(_dateController.text) ?? DateTime.now(),
                        );
                        if (picked != null) {
                          _dateController.text = DateFormat('yyyy-MM-dd').format(picked);
                        }
                      },
                    ),
                    const SizedBox(height: 12),
                    DropdownButtonFormField<int>(
                      value: _kriteriaId,
                      decoration: const InputDecoration(labelText: 'Kriteria'),
                      items: widget.kriterias
                          .map<DropdownMenuItem<int>>(
                            (k) => DropdownMenuItem<int>(
                              value: k['id'] as int,
                              child: Text(k['name'].toString()),
                            ),
                          )
                          .toList(),
                      onChanged: (value) => setState(() {
                        _kriteriaId = value;
                        _subKriteriaId = null;
                        _indikatorId = null;
                      }),
                      validator: (value) => value == null ? 'Pilih kriteria' : null,
                    ),
                    const SizedBox(height: 12),
                    DropdownButtonFormField<int>(
                      value: _subKriteriaId,
                      decoration: const InputDecoration(labelText: 'Kompetensi'),
                      items: subs
                          .map<DropdownMenuItem<int>>(
                            (sub) => DropdownMenuItem<int>(
                              value: sub['id'] as int,
                              child: Text(sub['name'].toString()),
                            ),
                          )
                          .toList(),
                      onChanged: (value) => setState(() {
                        _subKriteriaId = value;
                        _indikatorId = null;
                      }),
                      validator: (value) => value == null ? 'Pilih kompetensi' : null,
                    ),
                    const SizedBox(height: 12),
                    DropdownButtonFormField<int>(
                      value: _indikatorId,
                      decoration: const InputDecoration(labelText: 'Indikator'),
                      items: indikators
                          .map<DropdownMenuItem<int>>(
                            (indikator) => DropdownMenuItem<int>(
                              value: indikator['id'] as int,
                              child: Text(indikator['name'].toString()),
                            ),
                          )
                          .toList(),
                      onChanged: (value) => setState(() => _indikatorId = value),
                      validator: (value) => value == null ? 'Pilih indikator' : null,
                    ),
                    const SizedBox(height: 12),
                    TextFormField(
                      controller: _descriptionController,
                      maxLines: 4,
                      decoration: const InputDecoration(labelText: 'Deskripsi'),
                      validator: _required,
                    ),
                    const SizedBox(height: 12),
                    OutlinedButton.icon(
                      onPressed: () async {
                        final result = await FilePicker.platform.pickFiles(
                          type: FileType.custom,
                          allowedExtensions: const ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
                          withData: true,
                        );

                        if (result == null) return;

                        final picked = result.files.single;
                        if (picked.bytes == null && picked.path == null) {
                          if (context.mounted) {
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(content: Text('File tidak dapat dibaca. Coba pilih file lain.')),
                            );
                          }
                          return;
                        }

                        setState(() => _file = picked);
                      },
                      icon: const Icon(Icons.attach_file),
                      label: Text(_file == null ? 'Pilih File Evidence' : _file!.name),
                    ),
                    const SizedBox(height: 18),
                    Row(
                      children: [
                        Expanded(
                          child: FilledButton(
                            onPressed: _busy
                                ? null
                                : () async {
                                    if (!_formKey.currentState!.validate()) return;
                                    if (widget.evidence == null && _file == null) {
                                      ScaffoldMessenger.of(context).showSnackBar(
                                        const SnackBar(content: Text('File evidence wajib dipilih')),
                                      );
                                      return;
                                    }

                                    if (widget.evidence == null && (_file?.bytes == null && _file?.path == null)) {
                                      ScaffoldMessenger.of(context).showSnackBar(
                                        const SnackBar(content: Text('File evidence tidak valid')),
                                      );
                                      return;
                                    }

                                    setState(() => _busy = true);
                                    try {
                                      final payload = {
                                        'subject': _subjectController.text.trim(),
                                        'kelas': _kelasController.text.trim(),
                                        'tanggal': _dateController.text.trim(),
                                        'description': _descriptionController.text.trim(),
                                        'kriteria_id': _kriteriaId,
                                        'sub_kriteria_id': _subKriteriaId,
                                        'indikator_id': _indikatorId,
                                      };

                                      if (widget.evidence == null) {
                                        await _repository.store(
                                          fields: payload,
                                          file: _file?.path == null ? null : File(_file!.path!),
                                          fileBytes: _file?.bytes,
                                          fileName: _file?.name,
                                        );
                                      } else {
                                        await _repository.update(
                                          id: widget.evidence!['id'] as int,
                                          fields: payload,
                                          file: _file?.path == null ? null : File(_file!.path!),
                                          fileBytes: _file?.bytes,
                                          fileName: _file?.name,
                                        );
                                      }

                                      if (context.mounted) Navigator.pop(context, true);
                                    } catch (e) {
                                      if (context.mounted) {
                                        ScaffoldMessenger.of(context).showSnackBar(
                                          SnackBar(content: Text(e.toString())),
                                        );
                                      }
                                    } finally {
                                      if (mounted) setState(() => _busy = false);
                                    }
                                  },
                            child: _busy
                                ? const SizedBox(height: 18, width: 18, child: CircularProgressIndicator(strokeWidth: 2))
                                : const Text('Simpan'),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }

  String? _required(String? value) {
    if (value == null || value.trim().isEmpty) {
      return 'Wajib diisi';
    }
    return null;
  }
}
