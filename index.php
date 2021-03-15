<?php
const CALC_NUM_MIN = 1;
const CALC_NUM_MAX = 10;
const CALC_NUM_DEFALUT = 3;
$calc_num = isset($_GET['calc-num']) ? $_GET['calc-num'] : CALC_NUM_DEFALUT;
$calc_num = $calc_num > CALC_NUM_MAX ? CALC_NUM_MAX : $calc_num;
$calc_num = $calc_num < CALC_NUM_MIN ? CALC_NUM_MIN : $calc_num;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
  <style>
    /* 第1,5,6カラムに適用 */
    .col156-align td:nth-of-type(1), td:nth-of-type(5), td:nth-of-type(6) {
      text-align: right;
    }
  </style>
  <title>株式買付金額計算ツール</title>
</head>
<body>
  <h1>株式買付金額計算ツール</h1>
  <form id="calc-form" action="index.php" method="GET">
    <div class="d-flex flex-row my-2">
      <div>
        <button class="btn btn-outline-danger text-nowrap mx-3" type="reset">リセット</button>
      </div>
      <div>
        <div class="input-group">
          <input class="form-control" name="calc-num" type="number" placeholder="行数を入力して下さい" aria-describedby="calc-num-btn">
          <button id="calc-num-btn" class="btn btn-outline-primary" type="submit">変更</button>
        </div>
      </div>
      <div class="mx-3">
        <input id="purchase-capacity" class="form-control" type="number" placeholder="買付余力を入力して下さい" aria-describedby="calc-btn">
      </div>
    </div><!-- d-flex -->
    <div class="table-responsive">
      <table class="table table-striped table-bordered align-middle col156-align">
        <thead>
          <tr class="text-center text-nowrap">
            <th>No</th>
            <th>銘柄</th>
            <th>評価額</th>
            <th>株価</th>
            <th>購入金額</th>
            <th>購入株数</th>
          </tr>
        </thead>
        <tbody>
<?php for ($i = 1; $i <= $calc_num; $i++) { ?>
          <tr>
            <td><?= $i ?></td>
            <td>
              <!-- 銘柄 -->
              <input class="form-control" type="text">
            </td>
            <td>
              <!-- 評価額 -->
              <input id="valuation-<?= $i ?>" class="form-control" type="number">
            </td>
            <td>
              <!-- 株価 -->
              <input id="stock-price-<?= $i ?>" class="form-control" type="number">
            </td>
            <td>
              <!-- 購入金額 -->
              <output id="purchase-price-<?= $i ?>"></output>
            </td>
            <td>
              <!-- 購入株数 -->
              <output id="purchase-number-<?= $i ?>"></output>
            </td>
          </tr>
<?php } ?>
        </tbody>
      </table>
    </div><!-- table-responsive -->
  </form>
  <script>
    window.addEventListener('DOMContentLoaded', function() {
      document.getElementById('calc-form').addEventListener('input', function(e) {
        let valuation_sum = Number(document.getElementById('purchase-capacity').value);  // 買付余力で初期化
        <?php for ($i = 1; $i <= $calc_num; $i++) { ?>
          valuation_sum += Number(document.getElementById('valuation-<?= $i ?>').value);  // 評価額の合計を計算
        <?php } ?>
        let after_valuation = valuation_sum / <?= $calc_num ?>;  // 買付後の評価額を計算
        let purchase_price;  // 購入金額
        let purchase_number;  // 購入株数
        <?php for ($i = 1; $i <= $calc_num; $i++) { ?>
          purchase_price = after_valuation - Number(document.getElementById('valuation-<?= $i ?>').value);  // 購入金額を計算
          purchase_number = purchase_price / Number(document.getElementById('stock-price-<?= $i ?>').value)  // 購入株数を計算
          purchase_price = Math.round(purchase_price * 100) / 100;
          document.getElementById('purchase-price-<?= $i ?>').value = purchase_price;
          purchase_number = Math.floor(purchase_number);
          document.getElementById('purchase-number-<?= $i ?>').value = purchase_number;
        <?php } ?>
      });
    });
  </script>
</body>
</html>
