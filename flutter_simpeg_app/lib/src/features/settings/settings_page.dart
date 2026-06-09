import 'dart:io';

import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';

import '../../core/session/session_controller.dart';
import 'settings_repository.dart';

class SettingsPage extends StatefulWidget {
  const SettingsPage({super.key});

  @override
  State<SettingsPage> createState() => _SettingsPageState();
}

class _SettingsPageState extends State<SettingsPage> {
  Future<Map<String, dynamic>>? _future;
  final _formKey = GlobalKey<FormState>();
  final _phoneController = TextEditingController();
  final _addressController = TextEditingController();
  final _bioController = TextEditingController();
  final _websiteController = TextEditingController();
  final _twitterController = TextEditingController();
  final _instagramController = TextEditingController();
  final _linkedinController = TextEditingController();
  final _picker = ImagePicker();
  File? _pickedPhoto;
  bool _loading = false;
  String _theme = 'light';
  bool _initializedFields = false;

  @override
  void dispose() {
    _phoneController.dispose();
    _addressController.dispose();
    _bioController.dispose();
    _websiteController.dispose();
    _twitterController.dispose();
    _instagramController.dispose();
    _linkedinController.dispose();
    super.dispose();
  }

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    _load();
  }

  void _load() {
    final token = context.read<SessionController>().token;
    if (token == null) return;
    _initializedFields = false;
    setState(() {
      _future = SettingsRepository(token).index();
      _theme = context.read<SessionController>().themePreference;
    });
  }

  void _fillControllers(Map<String, dynamic> guru) {
    _phoneController.text = guru['phone']?.toString() ?? '';
    _addressController.text = guru['address']?.toString() ?? '';
    _bioController.text = guru['bio']?.toString() ?? '';
    _websiteController.text = guru['website']?.toString() ?? '';
    _twitterController.text = guru['social_media_twitter']?.toString() ?? '';
    _instagramController.text = guru['social_media_instagram']?.toString() ?? '';
    _linkedinController.text = guru['social_media_linkedin']?.toString() ?? '';
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
        final guru = Map<String, dynamic>.from(data['guru'] ?? {});
        final achievements = List<dynamic>.from(guru['achievements'] ?? []);
        final certifications = List<dynamic>.from(guru['certifications'] ?? []);
        if (!_initializedFields) {
          _fillControllers(guru);
          _initializedFields = true;
        }

        return DefaultTabController(
          length: 4,
          child: Column(
            children: [
              Container(
                margin: const EdgeInsets.all(16),
                padding: const EdgeInsets.all(18),
                decoration: BoxDecoration(
                  gradient: const LinearGradient(colors: [Color(0xFF0EA5E9), Color(0xFF2563EB)]),
                  borderRadius: BorderRadius.circular(24),
                ),
                child: Row(
                  children: [
                    CircleAvatar(
                      radius: 28,
                      backgroundColor: Colors.white.withOpacity(.15),
                      // backgroundImage: _pickedPhoto != null
                      //     ? FileImage(_pickedPhoto!)
                      //     : guru['photo_url'] == null
                      //         ? null
                      //         : NetworkImage(guru['photo_url'].toString()),
                      child: _pickedPhoto == null && guru['photo_url'] == null
                          ? Text(
                              (guru['name']?.toString().isNotEmpty ?? false) ? guru['name'].toString()[0].toUpperCase() : 'G',
                              style: const TextStyle(color: Colors.white, fontSize: 22, fontWeight: FontWeight.w800),
                            )
                          : null,
                    ),
                    const SizedBox(width: 14),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            guru['name']?.toString() ?? '-',
                            style: const TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.w800),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            guru['subject']?.toString() ?? '-',
                            style: TextStyle(color: Colors.white.withOpacity(.9)),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
              const TabBar(
                tabs: [
                  Tab(text: 'Profil'),
                  Tab(text: 'Foto'),
                  Tab(text: 'Pencapaian'),
                  Tab(text: 'Sertifikasi'),
                ],
              ),
              Expanded(
                child: TabBarView(
                  children: [
                    _ProfileTab(
                      loading: _loading,
                      theme: _theme,
                      onThemeChanged: (value) async {
                        if (token == null) return;
                        setState(() => _loading = true);
                        try {
                          await SettingsRepository(token).updateTheme(value);
                          await context.read<SessionController>().updateTheme(value);
                          setState(() => _theme = value);
                        } finally {
                          if (mounted) setState(() => _loading = false);
                        }
                      },
                      formKey: _formKey,
                      phoneController: _phoneController,
                      addressController: _addressController,
                      bioController: _bioController,
                      websiteController: _websiteController,
                      twitterController: _twitterController,
                      instagramController: _instagramController,
                      linkedinController: _linkedinController,
                      onSave: token == null
                          ? null
                          : () async {
                              if (!_formKey.currentState!.validate()) return;
                              setState(() => _loading = true);
                              try {
                                await SettingsRepository(token).updateProfile({
                                  'phone': _phoneController.text.trim(),
                                  'address': _addressController.text.trim(),
                                  'bio': _bioController.text.trim(),
                                  'website': _websiteController.text.trim(),
                                  'social_media_twitter': _twitterController.text.trim(),
                                  'social_media_instagram': _instagramController.text.trim(),
                                  'social_media_linkedin': _linkedinController.text.trim(),
                                });
                                await context.read<SessionController>().refreshProfile();
                                _load();
                              } finally {
                                if (mounted) setState(() => _loading = false);
                              }
                            },
                    ),
                    _PhotoTab(
                      photo: _pickedPhoto,
                      onPick: token == null
                          ? null
                          : () async {
                              final file = await _picker.pickImage(source: ImageSource.gallery, imageQuality: 85);
                              if (file == null) return;
                              setState(() {
                                _pickedPhoto = File(file.path);
                                _loading = true;
                              });
                              try {
                                await SettingsRepository(token).uploadPhoto(File(file.path));
                                await context.read<SessionController>().refreshProfile();
                                _pickedPhoto = null;
                                _load();
                              } finally {
                                if (mounted) setState(() => _loading = false);
                              }
                            },
                      busy: _loading,
                    ),
                    _AchievementTab(
                      items: achievements,
                      busy: _loading,
                      onAdd: token == null
                          ? null
                          : () async {
                              final result = await _showAchievementDialog();
                              if (result == null) return;
                              setState(() => _loading = true);
                              try {
                                await SettingsRepository(token).addAchievement(result);
                                _load();
                              } finally {
                                if (mounted) setState(() => _loading = false);
                              }
                            },
                      onDelete: token == null
                          ? null
                          : (id) async {
                              setState(() => _loading = true);
                              try {
                                await SettingsRepository(token).deleteAchievement(id);
                                _load();
                              } finally {
                                if (mounted) setState(() => _loading = false);
                              }
                            },
                    ),
                    _CertificationTab(
                      items: certifications,
                      busy: _loading,
                      onAdd: token == null
                          ? null
                          : () async {
                              final result = await _showCertificationDialog();
                              if (result == null) return;
                              setState(() => _loading = true);
                              try {
                                await SettingsRepository(token).addCertification(result);
                                _load();
                              } finally {
                                if (mounted) setState(() => _loading = false);
                              }
                            },
                      onDelete: token == null
                          ? null
                          : (id) async {
                              setState(() => _loading = true);
                              try {
                                await SettingsRepository(token).deleteCertification(id);
                                _load();
                              } finally {
                                if (mounted) setState(() => _loading = false);
                              }
                            },
                    ),
                  ],
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  Future<Map<String, dynamic>?> _showAchievementDialog() async {
    final title = TextEditingController();
    final year = TextEditingController();
    final description = TextEditingController();

    final result = await showDialog<Map<String, dynamic>>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Tambah Pencapaian'),
        content: SingleChildScrollView(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextField(controller: title, decoration: const InputDecoration(labelText: 'Judul')),
              TextField(controller: year, decoration: const InputDecoration(labelText: 'Tahun'), keyboardType: TextInputType.number),
              TextField(controller: description, decoration: const InputDecoration(labelText: 'Deskripsi'), maxLines: 3),
            ],
          ),
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Batal')),
          FilledButton(
            onPressed: () => Navigator.pop(context, {
              'title': title.text.trim(),
              'year': year.text.trim(),
              'description': description.text.trim(),
            }),
            child: const Text('Simpan'),
          ),
        ],
      ),
    );

    title.dispose();
    year.dispose();
    description.dispose();
    return result;
  }

  Future<Map<String, dynamic>?> _showCertificationDialog() async {
    final name = TextEditingController();
    final issuer = TextEditingController();
    final issuedDate = TextEditingController();
    final expiresDate = TextEditingController();
    final credentialUrl = TextEditingController();

    final result = await showDialog<Map<String, dynamic>>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Tambah Sertifikasi'),
        content: SingleChildScrollView(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextField(controller: name, decoration: const InputDecoration(labelText: 'Nama')),
              TextField(controller: issuer, decoration: const InputDecoration(labelText: 'Penerbit')),
              TextField(controller: issuedDate, decoration: const InputDecoration(labelText: 'Tanggal Terbit (YYYY-MM-DD)')),
              TextField(controller: expiresDate, decoration: const InputDecoration(labelText: 'Kadaluarsa (opsional)')),
              TextField(controller: credentialUrl, decoration: const InputDecoration(labelText: 'URL Kredensial (opsional)')),
            ],
          ),
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Batal')),
          FilledButton(
            onPressed: () => Navigator.pop(context, {
              'name': name.text.trim(),
              'issuer': issuer.text.trim(),
              'issued_date': issuedDate.text.trim(),
              'expires_date': expiresDate.text.trim(),
              'credential_url': credentialUrl.text.trim(),
            }),
            child: const Text('Simpan'),
          ),
        ],
      ),
    );

    name.dispose();
    issuer.dispose();
    issuedDate.dispose();
    expiresDate.dispose();
    credentialUrl.dispose();
    return result;
  }
}

class _ProfileTab extends StatelessWidget {
  const _ProfileTab({
    required this.loading,
    required this.theme,
    required this.onThemeChanged,
    required this.formKey,
    required this.phoneController,
    required this.addressController,
    required this.bioController,
    required this.websiteController,
    required this.twitterController,
    required this.instagramController,
    required this.linkedinController,
    required this.onSave,
  });

  final bool loading;
  final String theme;
  final ValueChanged<String> onThemeChanged;
  final GlobalKey<FormState> formKey;
  final TextEditingController phoneController;
  final TextEditingController addressController;
  final TextEditingController bioController;
  final TextEditingController websiteController;
  final TextEditingController twitterController;
  final TextEditingController instagramController;
  final TextEditingController linkedinController;
  final Future<void> Function()? onSave;

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Form(
        key: formKey,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            DropdownButtonFormField<String>(
              value: theme,
              decoration: const InputDecoration(labelText: 'Tema'),
              items: const [
                DropdownMenuItem(value: 'light', child: Text('Light')),
                DropdownMenuItem(value: 'dark', child: Text('Dark')),
                DropdownMenuItem(value: 'auto', child: Text('Auto')),
              ],
              onChanged: (value) {
                if (value != null) onThemeChanged(value);
              },
            ),
            const SizedBox(height: 12),
            TextFormField(controller: phoneController, decoration: const InputDecoration(labelText: 'Telepon')),
            const SizedBox(height: 12),
            TextFormField(controller: addressController, decoration: const InputDecoration(labelText: 'Alamat'), maxLines: 2),
            const SizedBox(height: 12),
            TextFormField(controller: bioController, decoration: const InputDecoration(labelText: 'Bio'), maxLines: 4),
            const SizedBox(height: 12),
            TextFormField(controller: websiteController, decoration: const InputDecoration(labelText: 'Website')),
            const SizedBox(height: 12),
            TextFormField(controller: twitterController, decoration: const InputDecoration(labelText: 'Twitter')),
            const SizedBox(height: 12),
            TextFormField(controller: instagramController, decoration: const InputDecoration(labelText: 'Instagram')),
            const SizedBox(height: 12),
            TextFormField(controller: linkedinController, decoration: const InputDecoration(labelText: 'LinkedIn')),
            const SizedBox(height: 20),
            FilledButton(
              onPressed: loading
                  ? null
                  : () async {
                      await onSave?.call();
                    },
              child: loading ? const CircularProgressIndicator() : const Text('Simpan Profil'),
            ),
          ],
        ),
      ),
    );
  }
}

class _PhotoTab extends StatelessWidget {
  const _PhotoTab({
    required this.photo,
    required this.onPick,
    required this.busy,
  });

  final File? photo;
  final VoidCallback? onPick;
  final bool busy;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: 132,
              height: 132,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: const Color(0xFFF8FAFC),
                border: Border.all(color: const Color(0xFFE2E8F0)),
                image: photo == null
                    ? null
                    : DecorationImage(
                        image: FileImage(photo!),
                        fit: BoxFit.cover,
                      ),
              ),
              child: photo == null
                  ? const Icon(Icons.person_rounded, size: 54, color: Color(0xFF94A3B8))
                  : null,
            ),
            const SizedBox(height: 16),
            const Text(
              'Pilih foto baru untuk memperbarui profil guru.',
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 16),
            FilledButton.icon(
              onPressed: busy ? null : onPick,
              icon: const Icon(Icons.photo_camera_outlined),
              label: Text(busy ? 'Memproses...' : 'Pilih Foto'),
            ),
          ],
        ),
      ),
    );
  }
}

class _AchievementTab extends StatelessWidget {
  const _AchievementTab({
    required this.items,
    required this.busy,
    required this.onAdd,
    required this.onDelete,
  });

  final List<dynamic> items;
  final bool busy;
  final VoidCallback? onAdd;
  final Future<void> Function(String id)? onDelete;

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        FilledButton.icon(
          onPressed: busy ? null : onAdd,
          icon: const Icon(Icons.add),
          label: const Text('Tambah Pencapaian'),
        ),
        const SizedBox(height: 12),
        ...items.map(
          (item) => Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ListTile(
              title: Text(item['title']?.toString() ?? '-'),
              subtitle: Text('${item['year'] ?? '-'}\n${item['description'] ?? ''}'),
              isThreeLine: true,
              trailing: IconButton(
                onPressed: busy || onDelete == null ? null : () => onDelete!(item['id'].toString()),
                icon: const Icon(Icons.delete_outline),
              ),
            ),
          ),
        ),
      ],
    );
  }
}

class _CertificationTab extends StatelessWidget {
  const _CertificationTab({
    required this.items,
    required this.busy,
    required this.onAdd,
    required this.onDelete,
  });

  final List<dynamic> items;
  final bool busy;
  final VoidCallback? onAdd;
  final Future<void> Function(String id)? onDelete;

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        FilledButton.icon(
          onPressed: busy ? null : onAdd,
          icon: const Icon(Icons.add),
          label: const Text('Tambah Sertifikasi'),
        ),
        const SizedBox(height: 12),
        ...items.map(
          (item) => Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ListTile(
              title: Text(item['name']?.toString() ?? '-'),
              subtitle: Text('${item['issuer'] ?? '-'}\n${item['issued_date'] ?? '-'}'),
              isThreeLine: true,
              trailing: IconButton(
                onPressed: busy || onDelete == null ? null : () => onDelete!(item['id'].toString()),
                icon: const Icon(Icons.delete_outline),
              ),
            ),
          ),
        ),
      ],
    );
  }
}
