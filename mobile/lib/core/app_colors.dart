import 'package:flutter/material.dart';

class AppColors {
  static const Color primary = Color(0xFF2563EB);
  static const Color ink = Color(0xFF101828);
  static const Color muted = Color(0xFF667085);
  static const Color card = Color(0xFFFFFFFF);
  static const Color line = Color(0xFFE4E7EC);
  static const Color danger = Color(0xFFD92D20);
  static const Color success = Color(0xFF027A48);
  static const Color warning = Color(0xFFB93815);
  static const Color authButton = Color(0xFFD63B3B);
  static const LinearGradient authGradient = LinearGradient(
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
    colors: [
      Color(0xFF4F5F8F),
      Color(0xFF8B5B8C),
      Color(0xFFE07A9B),
      Color(0xFFF1A14E),
    ],
  );
}
