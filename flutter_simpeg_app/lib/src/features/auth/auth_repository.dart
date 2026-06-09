import '../../core/network/api_client.dart';

class AuthRepository {
  AuthRepository({ApiClient? client}) : client = client ?? ApiClient();

  final ApiClient client;

  Future<Map<String, dynamic>> login({
    required String email,
    required String password,
  }) {
    return client.postJson(
      '/guru/login',
      data: {
        'email': email,
        'password': password,
        'device_name': 'flutter-guru-app',
      },
    );
  }

  Future<Map<String, dynamic>> me() {
    return client.getJson('/guru/me');
  }

  Future<Map<String, dynamic>> logout() {
    return client.postJson('/guru/logout');
  }
}
