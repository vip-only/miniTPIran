<?php define("base_url", flight::get("flight.base_url")) ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather&family=Poppins:wght@400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= base_url; ?>assets/css/bootstrap.min.css">
    <title>Immo</title>

    <style>

/* Header */
header {
    position: fixed;
    width: 100%;
    background:#3b7d4e;
    /* filter: blur(6px); */
    color: white;
    top: 0;
    z-index: 100;
}

.navbar {
    margin: 0;
    padding: 0;
    padding: 2.5em 0;
    display: flex;

}

.navbar .lien {
    display: flex;
    list-style: none;
    padding-right: 10%;

}
.a h1 , .a h2{
    color: #333;
}
.navbar .logo {

    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    font-size: x-large;
    margin-left: 5em;
    font-size: xx-large;
}

.navbar ul li {
    display: flex;
    margin: 0 15px;
    font-size: 20px;
    justify-content: center;
}

.navbar ul li a {
    color: var(--white);
    font-size: 1em;
    /* padding: 10px; */
    margin-top: 10px;
    transition: color 0.3s ease;
}

.navbar ul li a:hover {
    color:#abc4a4;
    text-decoration: none;
}

main {
    padding: 30px;
}
section {
    margin-top: 10em;
    margin-bottom: 1em;
}
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #333;
            color: white;
            padding: 20px;
            position: fixed;
            left: -260px;
            top: 0;
            transition: left 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 12px;
            border-radius: 5px;
            background: #3b7d4e;
            transition: background 0.3s;
            text-align: center;
        }

        .sidebar ul li a:hover {
            background: #567b50;
        }

        /* Toggle Button */
        .toggle-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #2F4F4F;
            color: #fff;
            border: none;
            padding: 12px 18px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            transition: background 0.3s;
            z-index: 1100;
        }

        .toggle-btn:hover {
            background: #222;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .sidebar {
                width: 220px;
            }

            .sidebar ul li a {
                padding: 10px;
                font-size: 14px;
            }

            .toggle-btn {
                top: 10px;
                left: 10px;
                padding: 10px 15px;
                font-size: 14px;
            }
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        footer {
            position: fixed;
            width: 100%;
            background-color: #333;
            transform: translateY(43rem);
        }

        .menu {
            padding: 1em 0;
        }

        .menu ul {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 1.2em;
            padding: 10px;
            transition: color 0.3s ease;

        }

        .menu ul li {
            margin: 0 15px;
            animation: tr infinite linear 30s;
        }

        .menu ul li a:hover {
            color: #ff6347;
        }


        @keyframes tr {
            0% {
                transform: translateX(-70rem);
            }

            100% {
                transform: translateX(90rem);
            }
        }
        .animal .nom:hover{
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <!-- Menu toggle button -->
    <button class="toggle-btn" onclick="toggleSidebar()" style="z-index:1000">☰</button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <button class="close-btn" id="close-btn" onclick="toggleSidebar()">✖</button>
        <h2>Dashboard</h2>
        <ul>
            <li><a href="<?= base_url; ?>accueil"> Accueil</a></li>
            <li><a href="<?= base_url; ?>statit"> Situation</a></li>
            <li><a href="<?= base_url; ?>updateTypes">Types</a></li>
            <li><a href="<?= base_url; ?>venteAnimal"> vente animaux</a></li>
            <li><a href="<?= base_url; ?>achatAnimal"> achat animaux</a></li>
            <li><a href="<?= base_url; ?>achatAliment"> achat aliment</a></li>
            <li><a href="<?= base_url; ?>ajoutCapital"> depot capital</a></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="main-content" id="main-content">
        <?php include($page . '.php'); ?>
    </div>
    <footer class="menu">
        <ul>
            <li><a href="#home">Luberri 3110</a></li>
            <li><a href="#services">Oceane 3110</a></li>
            <li><a href="#about">Safidy 3110</a></li>
        </ul>
    </footer>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const closeBtn = document.getElementById('close-btn');
            const mainContent = document.getElementById('main-content');

            if (sidebar.style.left === '0px') {
                sidebar.style.left = '-250px';
                closeBtn.style.display = 'none';
                mainContent.style.marginLeft = '0';
            } else {
                sidebar.style.left = '0';
                closeBtn.style.display = 'block';
                mainContent.style.marginLeft = '250px';
            }
        }
    </script>
</body>

</html>