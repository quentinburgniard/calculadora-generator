<?php
$score = 0;
$src = $fields = '';
if (isset($_POST['fields'])) {
  $fields = $_POST['fields'];
  $fields = json_decode($fields);
}

if (isset($fields->produtos) && isset($fields->preco) && isset($fields->quantidade) && isset($fields->unidade)) {
  $fields->preco = floatval(str_replace(',', '.', $fields->preco));
  $fields->quantidade = floatval(str_replace(',', '.', $fields->quantidade));

  if ($fields->unidade == 'g') {
    $fields->quantidade = $fields->quantidade / 1000;
    $fields->unidade = 'kg';
  }

  if ($fields->unidade == 'cl') {
    $fields->quantidade = $fields->quantidade / 100;
    $fields->unidade = 'l';
  }

  $reference = file_get_contents('https://api.digitalleman.com/product-prices?slug_eq=' . $fields->produtos);
  $reference = json_decode($reference);

  if (isset($reference) && !empty($reference) && isset($reference[0])) {
    $reference = $reference[0];

    if ($reference->unit == $fields->unidade && $fields->preco > 0 && $fields->quantidade > 0) {
      $difference = $reference->price / ($fields->preco * $fields->quantidade);
      if ($difference < 0.70) {
        $score = 1;
      } elseif ($difference >= 0.70 && $difference < 0.85) {
        $score = 2;
      } elseif ($difference >= 0.85 && $difference < 1) {
        $score = 3;
      } elseif ($difference == 1) {
        $score = 4;
      } elseif ($difference > 1 && $difference <= 1.15) {
        $score = 5;
      } elseif ($difference > 1.15 && $difference <= 1.30) {
        $score = 6;
      } elseif ($difference > 1.30) {
        $score = 7;
      }
    }
  }
}

switch ($score) {
  case 1:
    $src = 'loudly-crying-face.png';
    break;
  case 2:
    $src = 'sad-but-relieved-face.png';
    break;
  case 3:
    $src = 'confused-face.png';
    break;
  case 4:
    $src = 'neutral-face.png';
    break;
  case 5:
    $src = 'smirking-face.png';
    break;
  case 6:
    $src = 'beaming-face-with-smiling-eyes.png';
    break;
  case 7:
    $src = 'partying-face.png';
    break;
  default:
    $src = 'woozy-face.png';
}
?>
<html>
 <head>
  <title>Mesquinho Generator</title>
 </head>
 <body style="margin: 0;">
  <img src="https://static.digitalleman.com/mesquinho-generator/<?php echo $src ?>" alt="<?php echo $score . '/6'; ?>" width="60" height="60" style="position: absolute; right: 0; top: 0px;">
 </body>
</html>