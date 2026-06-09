import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import 'core/session/session_controller.dart';
import 'core/theme/app_theme.dart';
import 'features/auth/login_page.dart';
import 'features/home/home_shell.dart';
import 'features/splash/splash_page.dart';

class GuruMobileApp extends StatelessWidget {
  const GuruMobileApp({super.key});

  @override
  Widget build(BuildContext context) {
    final theme = AppTheme.build();

    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'SIMPEG Guru',
      theme: theme,
      home: Consumer<SessionController>(
        builder: (context, session, _) {
          if (!session.initialized) {
            return const SplashPage();
          }

          if (!session.isAuthenticated) {
            return const LoginPage();
          }

          return const HomeShell();
        },
      ),
    );
  }
}
