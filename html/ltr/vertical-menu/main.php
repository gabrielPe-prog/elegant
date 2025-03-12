<style>
    .full-width-image {
        width: 100%;
        height: 400px;
        overflow: hidden;
    }
</style>
<div class="app-content content">
      <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">


<!-- ICO Token balance & sale progress -->
<div class="row">
    <div class="col-md-12 col-12">
        <h6 class="my-2">Informações dos Ingressos</h6>
        <div class="card pull-up">
            <div class="card-content">
                <div class="card-body">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-8 col-12">
                                <p><strong>Total:</strong></p>
                                <h1><?php echo isset($resultado->total) ? $resultado->total : 0; ?> Ingressos</h1>
                                <p class="mb-0"> <strong>Registrados no sistema</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!--/ ICO Token balance & sale progress -->


<!-- Basic Horizontal Timeline -->
<div class="row match-height">
    <div class="col-xl-12 col-lg-12">
        <h6 class="my-2">Sorteio de Brindes</h6>
        <div class="card">
            <div class="card-content text-center">
                <img class="full-width-image" src="../../../app-assets/images/pages/blog-post.png" alt="Card image cap">
                <!--<div class="card-body">
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="card-link">Card link</a>
                    <a href="#" class="card-link">Another link</a>
                </div> -->
            </div>
          <!--  <div class="card-footer border-top-blue-grey border-top-lighten-5 text-muted">
                <span class="float-left">3 hours ago</span>
                <span class="float-right">
                    <a href="#" class="card-link">Read More <i class="fa fa-angle-right"></i></a>
                  </span>
            </div> -->
        </div>
    </div>
    
</div>
<!--/ Basic Horizontal Timeline -->
        </div>
      </div>
    </div>