<form class="d-flex m-3" action="<?= base_url() ?>" method="get">
    <input class="form-control me-2" type="search" name="search" placeholder="Search Pizza..."
        aria-label="Search" value="<?= isset($keyword) ? esc($keyword) : '' ?>">
    <button class="btn btn-outline-danger" type="submit">Search</button>
    <?php if (isset($keyword) && !empty($keyword)): ?>
        <a href="<?= base_url() ?>" class="btn btn-outline-secondary ms-2">Clear</a>
    <?php endif; ?>
</form>

<?php if (isset($keyword) && !empty($keyword) && empty($pizzas)): ?>
    <div class="alert alert-info alert-dismissible fade show position-fixed start-50 translate-middle-x mt-3" style="top: 3em" role="alert">
        No pizzas found matching "<?= esc($keyword) ?>". Try another search term.
    </div>
<?php endif; ?>

<div class="container mt-5 pb-5" id="pizzaList">
    <div class="row">
        <?php if (empty($pizzas)): ?>
            <div class="col text-center">
                <p>No pizzas available.</p>
            </div>
        <?php else: ?>
            <?php foreach ($pizzas as $pizza): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 card-hover">
                        <img
                            src="<?= $pizza['image'] ? base_url($pizza['image']) : base_url('images/no-image-available.jpg') ?>"
                            class="card-img-top" style="width: 100%; height: 21em" alt="<?= esc($pizza['name']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($pizza['name']) ?></h5>
                            <p class="card-text">
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= esc($pizza['category_description']) ?>">
                                    <i class="fas fa-info-circle"></i> <?= esc($pizza['category_name']) ?>
                                </button>
                            </p>
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