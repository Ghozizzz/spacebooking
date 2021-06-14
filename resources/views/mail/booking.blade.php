<html>
<body>
    <h3>Booking Details</h3>
    <p>Room: {{$userBooking->facilities->facilId}}</p>
    <p>Booking time: {{$userBooking->bookDate}} from {{$userBooking->bookTime}} for {{$userBooking->bookDuration}} minutes</p>
    <p>Purpose: [{{$userBooking->eventType}}] {{$userBooking->eventName}}</p>
    <p>Approval status: {{$userBooking->approvalReason}}</p>
    <p>Please confirm your booking by scanning the QR code in the room. You can confirm the booking 30 minutes before the book time.</p>
    <p>For more information, access the booking app: <a href="https://classmgmt.southeastasia.cloudapp.azure.com/" target="_blank">Class management app</a></p>
</body>
</html>