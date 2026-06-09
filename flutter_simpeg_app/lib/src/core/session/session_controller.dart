import 'package:flutter/foundation.dart';

import '../network/api_client.dart';
import '../storage/session_store.dart';
import '../../features/auth/auth_repository.dart';

class SessionController extends ChangeNotifier {
  final SessionStore _store = SessionStore();

  bool initialized = false;
  bool busy = false;
  String? errorMessage;

  String? token;
  Map<String, dynamic>? user;
  Map<String, dynamic>? guru;

  AuthRepository _authRepository({String? token}) => AuthRepository(
        client: ApiClient(token: token),
      );

  bool get isAuthenticated => token != null;
  String get themePreference => _store.themePreference;

  Future<void> bootstrap() async {
    await _store.load();
    token = _store.token;
    user = _store.user;
    guru = _store.guru;
    initialized = true;
    notifyListeners();

    if (token != null) {
      await refreshProfile(silent: true);
    }
  }

  Future<void> login({
    required String email,
    required String password,
  }) async {
    busy = true;
    errorMessage = null;
    notifyListeners();

    try {
      final result = await _authRepository().login(
        email: email,
        password: password,
      );

      token = result['token'] as String?;
      user = Map<String, dynamic>.from(result['user'] as Map);
      guru = result['guru'] == null
          ? null
          : Map<String, dynamic>.from(result['guru'] as Map);

      if (token != null) {
        await _store.saveSession(
          token: token!,
          user: user!,
          guru: guru,
        );
      }
    } catch (e) {
      errorMessage = e.toString().replaceFirst('Exception: ', '');
      rethrow;
    } finally {
      busy = false;
      notifyListeners();
    }
  }

  Future<void> refreshProfile({bool silent = false}) async {
    if (token == null) return;

    if (!silent) {
      busy = true;
      notifyListeners();
    }

    try {
      final authRepository = _authRepository(token: token);
      final result = await authRepository.me();
      user = Map<String, dynamic>.from(result['user'] as Map);
      guru = result['guru'] == null
          ? null
          : Map<String, dynamic>.from(result['guru'] as Map);
      await _store.saveSession(
        token: token!,
        user: user!,
        guru: guru,
      );
    } catch (_) {
      // Keep the existing session if the profile refresh fails temporarily.
    } finally {
      if (!silent) {
        busy = false;
        notifyListeners();
      }
    }
  }

  Future<void> updateTheme(String theme) async {
    await _store.saveTheme(theme);
    if (user != null) {
      user = {...user!, 'theme_preference': theme};
    }
    notifyListeners();
  }

  Future<void> logout() async {
    if (token != null) {
      try {
        await _authRepository(token: token).logout();
      } catch (_) {
        // Ignore logout failures and clear local session anyway.
      }
    }

    await _store.clear();
    token = null;
    user = null;
    guru = null;
    notifyListeners();
  }
}
