class ReviewModel {
  final String userName;
  final int rating; // 1 a 5
  final String comment;

  ReviewModel({
    required this.userName,
    required this.rating,
    required this.comment,
  });
}