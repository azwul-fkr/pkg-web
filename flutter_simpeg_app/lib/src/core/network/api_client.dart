import 'dart:io';
import 'dart:typed_data';

import 'package:dio/dio.dart';

import '../config/app_config.dart';
import 'api_exception.dart';

class ApiClient {
  ApiClient({String? token})
      : _dio = Dio(
          BaseOptions(
            baseUrl: AppConfig.apiBaseUrl,
            connectTimeout: const Duration(seconds: 20),
            receiveTimeout: const Duration(seconds: 20),
            responseType: ResponseType.json,
            headers: {
              'Accept': 'application/json',
            },
          ),
        ) {
    _token = token;
  }

  final Dio _dio;
  String? _token;

  set token(String? value) => _token = value;

  Options _options() {
    return Options(
      headers: {
        if (_token != null) 'Authorization': 'Bearer $_token',
      },
    );
  }

  Future<Map<String, dynamic>> getJson(
    String path, {
    Map<String, dynamic>? queryParameters,
  }) async {
    try {
      final response = await _dio.get(
        path,
        queryParameters: queryParameters,
        options: _options(),
      );

      return Map<String, dynamic>.from(response.data as Map);
    } on DioException catch (e) {
      throw ApiException(
        _messageFromDio(e),
        statusCode: e.response?.statusCode,
      );
    }
  }

  Future<Map<String, dynamic>> postJson(
    String path, {
    Map<String, dynamic>? data,
  }) async {
    try {
      final response = await _dio.post(
        path,
        data: data,
        options: _options(),
      );

      return Map<String, dynamic>.from(response.data as Map);
    } on DioException catch (e) {
      throw ApiException(
        _messageFromDio(e),
        statusCode: e.response?.statusCode,
      );
    }
  }

  Future<Map<String, dynamic>> putJson(
    String path, {
    Map<String, dynamic>? data,
  }) async {
    try {
      final response = await _dio.put(
        path,
        data: data,
        options: _options(),
      );

      return Map<String, dynamic>.from(response.data as Map);
    } on DioException catch (e) {
      throw ApiException(
        _messageFromDio(e),
        statusCode: e.response?.statusCode,
      );
    }
  }

  Future<Map<String, dynamic>> deleteJson(String path) async {
    try {
      final response = await _dio.delete(
        path,
        options: _options(),
      );

      return Map<String, dynamic>.from(response.data as Map);
    } on DioException catch (e) {
      throw ApiException(
        _messageFromDio(e),
        statusCode: e.response?.statusCode,
      );
    }
  }

  Future<Map<String, dynamic>> multipart(
    String path, {
    required Map<String, dynamic> fields,
    File? file,
    Uint8List? fileBytes,
    String? fileName,
    String fileFieldName = 'file',
  }) async {
    try {
      final filePart = file != null
          ? MapEntry(
              fileFieldName,
              await MultipartFile.fromFile(
                file.path,
                filename: fileName ?? file.path.split(Platform.pathSeparator).last,
              ),
            )
          : fileBytes != null
              ? MapEntry(
                  fileFieldName,
                  MultipartFile.fromBytes(
                    fileBytes,
                    filename: fileName,
                  ),
                )
              : null;

      final formData = FormData.fromMap({
        ...fields,
        if (filePart != null) filePart.key: filePart.value,
      });

      final response = await _dio.post(
        path,
        data: formData,
        options: _options(),
      );

      return Map<String, dynamic>.from(response.data as Map);
    } on DioException catch (e) {
      throw ApiException(
        _messageFromDio(e),
        statusCode: e.response?.statusCode,
      );
    }
  }

  String _messageFromDio(DioException e) {
    final data = e.response?.data;
    if (data is Map && data['message'] is String) {
      return data['message'] as String;
    }

    return e.message ?? 'Terjadi kesalahan jaringan.';
  }
}
