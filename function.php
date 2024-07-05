<?php
session_start();

//Membuat koneksi ke database
$conn = mysqli_connect("localhost","root","","stokbarang1");


//menambah barang baru
if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];

    $addtotable = mysqli_query($conn,"insert into stok (namabarang, deskripsi, stok) values('$namabarang','$deskripsi','$stok')");
    if($addtotable){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}


//Menambah barang masuk
if(isset($_POST['addbarangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstoksekarang = mysqli_query($conn,"select * from stok where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstoksekarang);

    $stoksekarang = $ambildatanya['stok'];
    $tambahkanstoksekarangdenganquantity = $stoksekarang+$qty;

    $addtomasuk = mysqli_query($conn,"insert into masuk (idbarang, keterangan, qty) values('$barangnya','$penerima','$qty')");
    $updatestokmasuk = mysqli_query($conn,"update stok set stok='$tambahkanstoksekarangdenganquantity' where idbarang='$barangnya'");
    if($addtomasuk&&$updatestokmasuk){
        header('location:masuk.php');
    } else {
        echo 'Gagal';
        header('location:masuk.php');
    }
}

//Menambah barang keluar
if(isset($_POST['addbarangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstoksekarang = mysqli_query($conn,"select * from stok where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstoksekarang);

    $stoksekarang = $ambildatanya['stok'];
    $tambahkanstoksekarangdenganquantity = $stoksekarang-$qty;

    $addtokeluar = mysqli_query($conn,"insert into keluar (idbarang, penerima, qty) values('$barangnya','$penerima','$qty')");
    $updatestokmasuk = mysqli_query($conn,"update stok set stok='$tambahkanstoksekarangdenganquantity' where idbarang='$barangnya'");
    if($addtokeluar&&$updatestokmasuk){
        header('location:keluar.php');
    } else {
        echo 'Gagal';
        header('location:keluar.php');
    }
}



//Update info barang
if(isset($_POST['updatebarang'])){
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    $update = mysqli_query($conn,"update stok set namabarang='$namabarang', deskripsi='$deskripsi' where idbarang ='$idb'");
    if($update){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}

//Menghapus barang dari stok
if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn, "delete from stok where idbarang='$idb'");
    if($hapus){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}

//Mengubah data barang masuk
if(isset($_POST['updatebarangmasuk'])){
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $deskripsi = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstok = mysqli_query($conn,"select * from stok where idbarang='$idb'");
    $stoknya = mysqli_fetch_array($lihatstok);
    $stokskrg = $stoknya['stok'];

    $qtyskrg = mysqli_query($conn, "select * from masuk where idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $kurangin = $stokskrg + $selisih;
        $kurangistoknya = mysqli_query($conn, "update stok set stok='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update masuk set qty='$qty', keterangan='$deskripsi' where idmasuk='$idm'");
            if($kurangistoknya&&$updatenya){
                header('location:masuk.php');
            } else {
                echo 'Gagal';
                header('location:masuk.php'); 
            }
    } else {
        $selisih = $qtyskrg-$qty;
        $kurangin = $stokskrg - $selisih;
        $kurangistoknya = mysqli_query($conn, "update stok set stok='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update masuk set qty='$qty', keterangan='$deskripsi' where idmasuk='$idm'");
            if($kurangistoknya&&$updatenya){
                header('location:masuk.php');
            } else {
                echo 'Gagal';
                header('location:masuk.php');
         }
    }
}




//Menghapus barang masuk
if(isset($_POST['hapusbarangmasuk'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST['idm'];

    $getdatastok = mysqli_query($conn,"select * from stok where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastok);
    $stok = $data['stok'];

    $selisih = $stok-$qty;

    $update = mysqli_query($conn,"update stok set stok='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn,"delete from masuk where idmasuk='$idm'");

    if($update&&$hapusdata){
        header('location:masuk.php');
    } else {
        header('location:masuk.php');
    }

}


//Mengubah data barang keluar
if(isset($_POST['updatebarangkeluar'])){
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatstok = mysqli_query($conn,"select * from stok where idbarang='$idb'");
    $stoknya = mysqli_fetch_array($lihatstok);
    $stokskrg = $stoknya['stok'];

    $qtyskrg = mysqli_query($conn, "select * from keluar where idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $kurangin = $stokskrg - $selisih;
        $kurangistoknya = mysqli_query($conn, "update stok set stok='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
            if($kurangistoknya&&$updatenya){
                header('location:keluar.php');
            } else {
                echo 'Gagal';
                header('location:keluar.php'); 
            }
    } else {
        $selisih = $qtyskrg-$qty;
        $kurangin = $stokskrg + $selisih;
        $kurangistoknya = mysqli_query($conn, "update stok set stok='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
            if($kurangistoknya&&$updatenya){
                header('location:keluar.php');
            } else {
                echo 'Gagal';
                header('location:keluar.php');
         }
    }
}




//Menghapus barang keluar
if(isset($_POST['hapusbarangkeluar'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];

    $getdatastok = mysqli_query($conn,"select * from stok where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastok);
    $stok = $data['stok'];

    $selisih = $stok+$qty;

    $update = mysqli_query($conn,"update stok set stok='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn,"delete from keluar where idkeluar='$idk'");

    if($update&&$hapusdata){
        header('location:keluar.php');
    } else {
        header('location:keluar.php');
    }

}

?>