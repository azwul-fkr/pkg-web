import 'dart:io';

import '../../core/network/api_client.dart';

class SettingsRepository {
  SettingsRepository(String token) : _client = ApiClient(token: token);

  final ApiClient _client;

  Future<Map<String, dynamic>> index() => _client.getJson('/guru/settings');

  Future<Map<String, dynamic>> updateProfile(Map<String, dynamic> payload) {
    return _client.putJson('/guru/settings/profile', data: payload);
  }

  Future<Map<String, dynamic>> uploadPhoto(File photo) {
    return _client.multipart(
      '/guru/settings/photo',
      fields: const {},
      file: photo,
      fileFieldName: 'photo',
    );
  }

  Future<Map<String, dynamic>> updateTheme(String theme) {
    return _client.putJson(
      '/guru/settings/theme',
      data: {'theme': theme},
    );
  }

  Future<Map<String, dynamic>> addAchievement(Map<String, dynamic> payload) {
    return _client.postJson('/guru/settings/achievements', data: payload);
  }

  Future<Map<String, dynamic>> deleteAchievement(String id) {
    return _client.deleteJson('/guru/settings/achievements/$id');
  }

  Future<Map<String, dynamic>> addCertification(Map<String, dynamic> payload) {
    return _client.postJson('/guru/settings/certifications', data: payload);
  }

  Future<Map<String, dynamic>> deleteCertification(String id) {
    return _client.deleteJson('/guru/settings/certifications/$id');
  }
}
