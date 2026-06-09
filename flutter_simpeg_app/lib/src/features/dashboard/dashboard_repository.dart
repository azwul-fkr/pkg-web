import '../../core/network/api_client.dart';

class DashboardRepository {
  DashboardRepository(String token) : _client = ApiClient(token: token);

  final ApiClient _client;

  Future<Map<String, dynamic>> fetch() {
    return _client.getJson('/guru/dashboard');
  }
}
