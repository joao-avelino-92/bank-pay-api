<html>
<head>
    <title>Hi, you receive a transfer on Digital-bank-api!</title>
</head>
<body>
<h1>Hi, you receive a transfer on Digital-bank-api!</h1>
<h2> Payer: {{$transferDataArray['payeerName']}} - {{$transferDataArray['payeerEmail']}} </h2>
<h2> Payee: {{$transferDataArray['payeeName']}} - {{$transferDataArray['payeeEmail']}}</h2>
<h2> Value: {{$transferDataArray['value']}}</h2>
</body>
</html>
