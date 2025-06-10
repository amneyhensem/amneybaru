<?php
// Database connection parameters
$servername = 'localhost';
$username = 'root';
$password_db = ''; // Replace with your actual MySQL password
$dbname = 'donasi';

// Create connection
$conn = new mysqli($servername, $username, $password_db, $dbname);
$conn->set_charset("utf8");

// Check connection
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Fetch donation data
$sql = "SELECT name, email, gender, amount, date FROM bayaran";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Donation Records</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
            color: #222;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .admin-container {
            max-width: 1100px;
            margin: 40px auto;
            background: rgba(255,255,255,0.97);
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px rgba(238,9,121,0.15), 0 1.5px 8px rgba(255,106,0,0.08);
            padding: 2.5rem 2rem 2rem 2rem;
            animation: fadeIn 1.2s cubic-bezier(.68,-0.55,.27,1.55);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(40px);}
            to { opacity: 1; transform: translateY(0);}
        }
        h2 {
            color: #ee0979;
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
            font-weight: 800;
            text-shadow: 0 2px 8px rgba(255,106,0,0.08);
        }
        .table-responsive {
            overflow-x: auto;
            border-radius: 1rem;
            box-shadow: 0 2px 12px rgba(238,9,121,0.07);
            background: #fff;
            animation: tableSlideIn 1.2s 0.2s cubic-bezier(.68,-0.55,.27,1.55) backwards;
        }
        @keyframes tableSlideIn {
            from { opacity: 0; transform: scale(0.97);}
            to { opacity: 1; transform: scale(1);}
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }
        th, td {
            padding: 16px 14px;
            text-align: left;
            border-bottom: 1px solid #f3f3f3;
            font-size: 1.08rem;
            transition: background 0.3s;
        }
        th {
            background: linear-gradient(90deg, #ee0979 60%, #ff6a00 100%);
            color: #fff;
            font-weight: 700;
            letter-spacing: 0.5px;
            border: none;
            position: sticky;
            top: 0;
            z-index: 2;
        }
        tr {
            transition: box-shadow 0.3s, transform 0.3s;
        }
        tr:hover {
            background: #fff3e6;
            box-shadow: 0 2px 12px rgba(255,106,0,0.08);
            transform: scale(1.01);
        }
        .icon {
            color: #ee0979;
            margin-right: 8px;
            animation: iconPulse 1.5s infinite alternate;
        }
        @keyframes iconPulse {
            from { transform: scale(1);}
            to { transform: scale(1.15);}
        }
        .no-data {
            text-align: center;
            color: #ee0979;
            font-size: 1.2rem;
            padding: 2rem 0;
        }
        @media (max-width: 800px) {
            .admin-container {
                padding: 1.2rem 0.5rem;
            }
            table, th, td {
                font-size: 0.98rem;
            }
        }
        @media (max-width: 600px) {
            .admin-container {
                padding: 0.5rem 0.1rem;
            }
            table {
                min-width: 400px;
            }
            th, td {
                padding: 10px 6px;
            }
            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
<div class="admin-container">
    <h2><i class="fa-solid fa-gauge-high icon"></i>Donation Records</h2>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><i class="fa-solid fa-user"></i> Name</th>
                    <th><i class="fa-solid fa-envelope"></i> Email</th>
                    <th><i class="fa-solid fa-venus-mars"></i> Gender</th>
                    <th><i class="fa-solid fa-dollar-sign"></i> Amount (USD)</th>
                    <th><i class="fa-solid fa-calendar-days"></i> Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if the query was successful
                if (!$result) {
                    echo "<tr><td colspan='5' class='no-data'>Query failed: " . $conn->error . "</td></tr>";
                } else if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".htmlspecialchars($row["name"])."</td>";
                        echo "<td>".htmlspecialchars($row["email"])."</td>";
                        echo "<td>".ucfirst(htmlspecialchars($row["gender"]))."</td>";
                        echo "<td>$".number_format($row["amount"], 2)."</td>";
                        echo "<td>".htmlspecialchars($row["date"])."</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='no-data'><i class='fa-solid fa-circle-info'></i> No donations found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Motion graphic: animated floating dots background -->
<canvas id="bg-canvas" style="position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:-1;pointer-events:none;"></canvas>
<script>
const canvas = document.getElementById('bg-canvas');
const ctx = canvas.getContext('2d');
let dots = [];
function resizeCanvas() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
}
function createDots() {
    dots = [];
    for (let i = 0; i < 30; i++) {
        dots.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            r: 8 + Math.random() * 12,
            dx: (Math.random() - 0.5) * 0.7,
            dy: (Math.random() - 0.5) * 0.7,
            color: Math.random() > 0.5 ? 'rgba(238,9,121,0.13)' : 'rgba(255,106,0,0.13)'
        });
    }
}
function animateDots() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    for (let dot of dots) {
        ctx.beginPath();
        ctx.arc(dot.x, dot.y, dot.r, 0, 2 * Math.PI);
        ctx.fillStyle = dot.color;
        ctx.fill();
        dot.x += dot.dx;
        dot.y += dot.dy;
        if (dot.x < 0 || dot.x > canvas.width) dot.dx *= -1;
        if (dot.y < 0 || dot.y > canvas.height) dot.dy *= -1;
    }
    requestAnimationFrame(animateDots);
}
window.addEventListener('resize', () => {
    resizeCanvas();
    createDots();
});
resizeCanvas();
createDots();
animateDots();
</script>
</body>
</html>
