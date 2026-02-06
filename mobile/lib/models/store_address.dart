class StoreAddress {
  final String address;
  final String city;
  final String province;
  final double? latitude;
  final double? longitude;

  StoreAddress({
    required this.address,
    required this.city,
    required this.province,
    this.latitude,
    this.longitude,
  });

  factory StoreAddress.fromJson(Map<String, dynamic> json) {
    return StoreAddress(
      address: (json['address'] ?? '').toString(),
      city: (json['city'] ?? '').toString(),
      province: (json['province'] ?? '').toString(),
      latitude: json['latitude'] != null ? double.tryParse(json['latitude'].toString()) : null,
      longitude: json['longitude'] != null ? double.tryParse(json['longitude'].toString()) : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'address': address,
      'city': city,
      'province': province,
      'latitude': latitude,
      'longitude': longitude,
    };
  }
}
