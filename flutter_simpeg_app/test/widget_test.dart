import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:google_fonts/google_fonts.dart';

import 'package:guru_mobile_app/src/core/theme/app_theme.dart';

void main() {
  TestWidgetsFlutterBinding.ensureInitialized();

  setUpAll(() {
    GoogleFonts.config.allowRuntimeFetching = false;
  });

  testWidgets('AppTheme builds and can render a simple screen', (tester) async {
    await tester.pumpWidget(
      MaterialApp(
        theme: AppTheme.build(),
        home: const Scaffold(
          body: Center(
            child: Text('SIMPEG Guru'),
          ),
        ),
      ),
    );

    expect(find.text('SIMPEG Guru'), findsOneWidget);
    expect(find.byType(Scaffold), findsOneWidget);
  });
}
