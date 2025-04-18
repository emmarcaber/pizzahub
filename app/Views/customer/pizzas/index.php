<form class="d-flex m-3">
    <input class="form-control me-2" type="search" placeholder="Search Pizza..." aria-label="Search">
    <button class="btn btn-outline-danger" type="submit">Search</button>
</form>

<div class="container mt-5 pb-5">
    <div class="row">
        <?php if (empty($pizzas)): ?>
            <div class="col text-center">
                <p>No pizzas available.</p>
            </div>
        <?php else: ?>
            <?php foreach ($pizzas as $pizza): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img
                            src="<?= $pizza['image'] ? base_url($pizza['image']) : base_url('images/no-image-available.jpg') ?>"
                            class="card-img-top" style="width: 100%; height: 21em" alt="<?= esc($pizza['name']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($pizza['name']) ?></h5>
                            <p class="card-text"><?= esc($pizza['description']) ?></p>
                            <p class="card-text"><strong>Price:</strong> &#8369;<?= number_format($pizza['price'], 2) ?></p>
                            <a href="<?= route_to('cart.add', $pizza['id']) ?>" class="btn btn-danger">Add to Cart</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>