import 'dart:convert';

import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:shared_preferences/shared_preferences.dart';

class SessionStore {
  static const _tokenKey = 'guru_mobile_token';
  static const _userKey = 'guru_mobile_user';
  static const _guruKey = 'guru_mobile_guru';
  static const _themeKey = 'guru_mobile_theme';

  final FlutterSecureStorage _secureStorage = const FlutterSecureStorage();

  String? token;
  Map<String, dynamic>? user;
  Map<String, dynamic>? guru;
  String themePreference = 'light';

  Future<void> load() async {
    final prefs = await SharedPreferences.getInstance();
    token = await _secureStorage.read(key: _tokenKey);
    themePreference = prefs.getString(_themeKey) ?? 'light';

    final userRaw = prefs.getString(_userKey);
    final guruRaw = prefs.getString(_guruKey);

    user = userRaw == null ? null : Map<String, dynamic>.from(jsonDecode(userRaw) as Map);
    guru = guruRaw == null ? null : Map<String, dynamic>.from(jsonDecode(guruRaw) as Map);
  }

  Future<void> saveSession({
    required String token,
    required Map<String, dynamic> user,
    Map<String, dynamic>? guru,
  }) async {
    final prefs = await SharedPreferences.getInstance();
    await _secureStorage.write(key: _tokenKey, value: token);
    await prefs.setString(_userKey, jsonEncode(user));

    if (guru != null) {
      await prefs.setString(_guruKey, jsonEncode(guru));
    } else {
      await prefs.remove(_guruKey);
    }

    this.token = token;
    this.user = user;
    this.guru = guru;
  }

  Future<void> saveTheme(String theme) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_themeKey, theme);
    themePreference = theme;
  }

  Future<void> clear() async {
    final prefs = await SharedPreferences.getInstance();
    await _secureStorage.delete(key: _tokenKey);
    await prefs.remove(_userKey);
    await prefs.remove(_guruKey);

    token = null;
    user = null;
    guru = null;
  }
}
