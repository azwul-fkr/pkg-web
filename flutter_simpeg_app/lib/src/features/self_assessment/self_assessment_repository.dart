import '../../core/network/api_client.dart';

class SelfAssessmentRepository {
  SelfAssessmentRepository(String token) : _client = ApiClient(token: token);

  final ApiClient _client;

  Future<Map<String, dynamic>> index() => _client.getJson('/guru/self-assessments');

  Future<Map<String, dynamic>> show(int id) => _client.getJson('/guru/self-assessments/$id');

  Future<Map<String, dynamic>> create(int periodId) {
    return _client.postJson(
      '/guru/self-assessments',
      data: {'period_id': periodId},
    );
  }

  Future<Map<String, dynamic>> saveDraftOrSubmit({
    required int id,
    required Map<String, dynamic> scores,
    required Map<String, dynamic> comments,
    required bool submit,
  }) {
    return _client.putJson(
      '/guru/self-assessments/$id',
      data: {
        'scores': scores,
        'comments': comments,
        'submit_type': submit ? 'submit' : 'draft',
      },
    );
  }

  Future<Map<String, dynamic>> submit(int id) {
    return _client.postJson('/guru/self-assessments/$id/submit');
  }
}
