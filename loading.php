<!DOCTYPE html>
<html lang="en">

<head>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/simplebar@latest/dist/simplebar.css">
    <link rel="stylesheet" href="vendor/css/style.css">
</head>


<?php
require('vendor/autoload.php');
require('db.php');


if (isset($_GET['page']) && !empty($_GET['page'])) {
    $currentPage = $_GET['page'];
} else {
    $currentPage = 1;
}
if (isset($_GET['size']) && !empty($_GET['size']) && $_GET['size'] > 25) {
    $size = $_GET['size'];
} else {
    $size = 25;
}




$headers = array('Accept' => 'application/json', 'Authorization' => 'Bearer NofTc_AQrVn1TK3z_8EdI7nJLwA');

try {
    $request = Requests::get('https://api.lexoffice.io/v1/voucherlist?page=' . $currentPage . '&size=' . $size . '&voucherType=purchaseinvoice,invoice&voucherStatus=open,paid,paidoff,voided', $headers);



    $list = json_decode($request->body);

    $response = $list->content;




    $startFrom = ($currentPage * $size) - $size;

    $lastPage = ceil($list->totalElements / $size);
    $firstPage = 1;
    $nextPage = $currentPage + 1;
    $previousPage = $currentPage - 1;

    $stmt = $conn->prepare("SELECT insertContent(?,?,?,?,?,?,?,?,?,?,?,?,?,?) as `inserted`;");
    foreach ($response as $item) {
    

        if (isset($item->contactId)) {

            $stmt->execute([$item->id, $item->voucherType, $item->voucherStatus, $item->voucherNumber, $item->voucherDate, $item->createdDate, $item->updatedDate, $item->dueDate, $item->contactId, $item->contactName, $item->totalAmount, $item->openAmount, $item->currency, $item->archived]);
        } else {
            $item->contactId=null;

            $stmt->execute([$item->id, $item->voucherType, $item->voucherStatus, $item->voucherNumber, $item->voucherDate, $item->createdDate, $item->updatedDate, $item->dueDate, null, $item->contactName, $item->totalAmount, $item->openAmount, $item->currency, $item->archived]);
        }
    }
} catch (Exception $e) {
    $response = [];

    echo ($e);
}

// ckeck all item
if(isset($_POST['submit'])){
    if(isset($_POST['id'])){
        foreach($_POST['id'] as $id){
            $CheckedId [] = $id;
        }
    }
}

?>
<section >
<div class="overlay"></div>

<form method="GET" style="height:100%">
<div class="table-responsive col-12" style="height:100%" id="scroll" data-simplebar data-simplebar-auto-hide="false">
    <table class="table table-dark table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">voucherType</th>
                <th scope="col">voucherStatus</th>
                <th scope="col">voucherNumber</th>

                <th scope="col">voucherDate</th>
                <th scope="col">createdDate</th>
                <th scope="col">updatedDate</th>

                <th scope="col">dueDate</th>
                <th scope="col">contactId</th>
                <th scope="col">contactName</th>
                <th scope="col">totalAmount</th>

                <th scope="col">openAmount</th>
                <th scope="col">currency</th>
                <th scope="col">archived</th>
                <th scope="col">check</th>


            </tr>
        </thead>
        <tbody>
            <?php foreach ($response as $item) {  ?>


                <tr>
                    <th scope="row"><?php echo  $item->id ?> </th>
                    <td><?php echo  $item->voucherType ?></td>
                    <td><?php echo  $item->voucherStatus ?></td>
                    <td><?php echo  $item->voucherNumber ?></td>
                    <td><?php echo  $item->voucherDate ?></td>
                    <td><?php echo  $item->createdDate ?></td>
                    <td><?php echo  $item->updatedDate ?></td>
                    <td><?php echo  $item->dueDate ?></td>
                    <td><?php echo  $item->contactId ?></td>
                    <td><?php echo  $item->contactName ?></td>

                    <td><?php echo  $item->totalAmount ?></td>
                    <td><?php echo  $item->openAmount ?></td>
                    <td><?php echo  $item->currency ?></td>
                    <td><?php if ($item->archived) {
                            echo "true";
                        } else {
                            echo "false";
                        } ?></td>

                    <td>
                    <input class="form-check-input" type="checkbox" name="id[]" value="<?php echo $item->id ?>" id="flexCheckDefault<?php echo $item->id ?>">
                    </td>


                </tr>

            <?php } ?>

        </tbody>
    </table>
</div>
    <div class="d-grid gap-2 col-3 mx-auto p-2">
        <button class="btn btn-primary" type="submit" name="submit">Submit</button>
    </div>
</form>
</section>

    <div>
        <nav aria-label="Page navigation" class="custome-nav">
            <ul class="pagination justify-content-end">
                <?php if ($currentPage != $firstPage) { ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $firstPage ?>" tabindex="-1" aria-label="Previous">
                            <span aria-hidden="true">First</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($currentPage >= 2) { ?>
                    <li class="page-item"><a class="page-link" href="?page=<?php echo $previousPage ?>"><?php echo $previousPage ?></a></li>
                <?php } ?>
                <li class="page-item active"><a class="page-link" href="?page=<?php echo $currentPage ?>"><?php echo $currentPage ?></a></li>
                <?php if ($currentPage != $lastPage) { ?>
                    <li class="page-item"><a class="page-link" href="?page=<?php echo $nextPage ?>"><?php echo $nextPage ?></a></li>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $lastPage ?>" aria-label="Next">
                            <span aria-hidden="true">Last</span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>


    </div>

    <?php

    ?>
        <script src="https://unpkg.com/simplebar@latest/dist/simplebar.min.js"></script>

    </body>

</html>