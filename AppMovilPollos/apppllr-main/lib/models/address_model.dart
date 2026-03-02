class AddressModel {
  final String street;
  final String district;
  final String reference;

  AddressModel({
    required this.street,
    required this.district,
    required this.reference,
  });

  String get fullAddress =>
      '$street, $district (${reference.isEmpty ? "" : reference})';
}