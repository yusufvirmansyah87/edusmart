<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <title>Halaman Admin BookStore</title>
    <style>
     @media (max-width: 992px) {
    #mySidebar {
        position: fixed;
        top: 56px;
        bottom: 0;
        left: 0;
        z-index: 1050;
        overflow-y: auto;
        transition: all 0.3s;
        transform: translateX(-100%);
        width: 80%;
        max-width: 300px;
        background: #f8f9fa;
        padding: 1rem 0;
    }

    #mySidebar.show {
        transform: translateX(0);
    }
}

/* Tambahkan ini untuk memastikan sidebar muncul pada layar lg atau lebih besar */
@media (min-width: 992px) {
    #mySidebar {
        position: relative;
        transform: translateX(0);
    }
}
    </style>