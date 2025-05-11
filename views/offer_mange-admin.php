<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>offer-management</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/offer_mange-admin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="admin.html">Back</a></li>
            </ul>
        </nav>
    </header>
<main>
    <section id="merchant-section">
        <h2>Merchants</h2>
        
        <div class="merchant-list">
            <div class="merchant-item">
                <p>Name: Morning Coffee | Rating: 4.5</p>
                <div class="merchant-actions">
                    <button class="edit-btn" data-id="101"> Accept</button>
                    <button class="delete-btn" data-id="101"><i class="bi bi-trash"></i> Delete</button>
                </div>
            </div>
            <div class="merchant-item">
                <p>Name: Hope Bookstore | Rating: 4.0</p>
                <div class="merchant-actions">
                    <button class="edit-btn" data-id="102"> Accept</button>
                    <button class="delete-btn" data-id="102"><i class="bi bi-trash"></i> Delete</button>
                </div>
            </div>
            <div class="merchant-item">
                <p>Name: Tech Hub | Rating: 4.8</p>
                <div class="merchant-actions">
                    <button class="edit-btn" data-id="103"> Accept</button>
                    <button class="delete-btn" data-id="103"><i class="bi bi-trash"></i> Delete</button>
                </div>
            </div>
        </div>
    </section>
</main>
    
</body>
</html>