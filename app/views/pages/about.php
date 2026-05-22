<?php ob_start(); ?>

<div class="container pt-5">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-6 my-auto">
            <h1>Welcome to About Page</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat numquam non, eum, sunt corrupti sequi, voluptatum possimus perspiciatis quasi tempore assumenda corporis accusantium quo officia!</p>
        </div>
        <div class="col-12 col-md-6 col-lg-6">
            <img src="https://images.pexels.com/photos/35927296/pexels-photo-35927296.jpeg" alt="About Us" class="img-fluid rounded-5">
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';