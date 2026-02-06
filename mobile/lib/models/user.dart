class User {
  final int id;
  final String fullName;
  final String email;
  final String? phone;
  final String? profilePhotoUrl;
  final List<String> roles;

  User({
    required this.id,
    required this.fullName,
    required this.email,
    required this.roles,
    this.phone,
    this.profilePhotoUrl,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    final rolesJson = json['roles'];
    final roles = <String>[];
    if (rolesJson is List) {
      for (final item in rolesJson) {
        if (item is Map && item['name'] != null) {
          roles.add(item['name'].toString());
        } else if (item is String) {
          roles.add(item);
        }
      }
    }
    return User(
      id: json['id'] as int,
      fullName: (json['full_name'] ?? '').toString(),
      email: (json['email'] ?? '').toString(),
      phone: json['phone']?.toString(),
      profilePhotoUrl: json['profile_photo_url']?.toString(),
      roles: roles,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'full_name': fullName,
      'email': email,
      'phone': phone,
      'profile_photo_url': profilePhotoUrl,
      'roles': roles,
    };
  }
}
