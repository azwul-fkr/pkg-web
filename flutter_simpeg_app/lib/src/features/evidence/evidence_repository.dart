import 'dart:io';
import 'dart:typed_data';

import '../../core/network/api_client.dart';

class EvidenceRepository {
  EvidenceRepository(String token) : _client = ApiClient(token: token);

  final ApiClient _client;

  Future<Map<String, dynamic>> index() => _client.getJson('/guru/evidences');

  Future<Map<String, dynamic>> show(int id) => _client.getJson('/guru/evidences/$id');

  Future<Map<String, dynamic>> store({
    required Map<String, dynamic> fields,
    File? file,
    Uint8List? fileBytes,
    String? fileName,
  }) {
    return _client.multipart(
      '/guru/evidences',
      fields: fields,
      file: file,
      fileBytes: fileBytes,
      fileName: fileName,
    );
  }

  Future<Map<String, dynamic>> update({
    required int id,
    required Map<String, dynamic> fields,
    File? file,
    Uint8List? fileBytes,
    String? fileName,
  }) {
    if (file == null && fileBytes == null) {
      return _client.postJson('/guru/evidences/$id', data: fields);
    }

    return _client.multipart(
      '/guru/evidences/$id',
      fields: fields,
      file: file,
      fileBytes: fileBytes,
      fileName: fileName,
    );
  }

  Future<Map<String, dynamic>> delete(int id) => _client.deleteJson('/guru/evidences/$id');
}
