<?php include( 'header.php' ); ?>

<style type="text/css">


::-webkit-scrollbar {
  -webkit-appearance: none;
  width: 4px;
}
::-webkit-scrollbar-thumb {
  border-radius: 4px;
  background-color: rgba(0,0,0,.5);
  -webkit-box-shadow: 0 0 1px rgba(255,255,255,.5);
}
#bodyModal{
  padding-top: 0px;
  padding-bottom: 0px;
  padding-left: 15px;
  max-height: 400px;
}
#modalImage{
  min-width: 607px;
}
#imageCol{
  /*border: solid;*/
  padding-left: 0;
  padding-right: 0
}

#myCarousel img{
  margin: 0;
  padding-left: 0px;
  padding-right: 0px;
  max-width: 100%;
  height: 400px;
}
.carousel-inner > .item > img,
.carousel-inner > .item > a > img {
  width: 100%;
  margin: auto;
}
#textCol{
  padding-left: 15px;
  padding-right: 15px;
  max-width: 294px;
  max-height: 400px;
  overflow-y: hidden;
}
#textCol:hover{
  overflow-y: scroll;
}
#textCol img{
  height: 40px;
  width: 40px;
  padding: 0px;
}

#posterInfo{
  /*padding-left: 10px;*/
  padding-top: 10px;
  padding-bottom: 10px;
  position: relative;

}
#imgDesc{
  padding-bottom: 10px;
  padding-left: 15px;
  overflow-y: hidden;
  max-height: 150px;
  width: auto;
}
#imgDesc:hover{
  overflow-y: scroll;
}
#lcsRow{
  margin-left: 10px;
  padding-bottom: 5px;
  padding-top: 5px;
}
#likeRow{
  padding-left: 15px;
}
#postedComments{
  background: #E7ECEB;
  padding-top: 10px;
  padding-bottom: 10px;
}
#postedComments a{
  margin: 0;
  padding: 0;
}
#dateText{
  font-size: 10px;
}
#commentBox{
  background: #E7ECEB;
  padding-right: 0px;
  padding-top: 10px;
}
#commentBox textarea{
  width: 92%;
  resize: none;
  padding: 0;
  margin: 0;
}
#imageButton{
  padding-top:85px;
}

</style>

<div class="container">


<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#modalImage" id="imageButton">Open Modal</button>
<!--MODAL-->
<div id="modalImage" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!--MODAL CONTENT-->
    <div class="modal-content">
      <div class="modal-body" id="bodyModal">
        <div class="row" >

          <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8" id="imageCol">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                  <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                  <li data-target="#myCarousel" data-slide-to="1"></li>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                  <div class="item active">
                    <img src="http://www.bealecorner.com/trv900/respat/trv-res.jpg" alt="test1" >
                  </div>

                  <div class="item">
                    <img src="https://raw.githubusercontent.com/plu/JPSimulatorHacks/master/Data/test.png" alt="test2">
                  </div>

                <!-- Left and right controls -->
                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                  <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                  <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
              </div>
            </div>
          </div><!--COL 8-->

          <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" id="textCol">
            <div class="row" id="posterInfo">
              <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
                <img src="https://scontent-lga3-1.xx.fbcdn.net/hphotos-xfa1/v/t1.0-9/11903854_10204926490586041_3518895085011382707_n.jpg?oh=74f07a922533f077ca413e44bbe39f7c&oe=57457CB8" alt="" />
              </div>
              <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9">
                <div class="row">
                  DONAT VUCETAJ
                </div>
                <div class="row" id="dateText">
                  DATE
                </div>
              </div>
            </div>

            <div class="row" id="imgDesc">
              <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ante lectus, sodales a eros a, auctor laoreet leo. Sed sit amet leo a nibh bibendum dapibus. Proin vitae odio quis tellus mattis consequat. Sed enim metus, pretium non feugiat ultricies, laoreet vel purus. Sed commodo enim ac leo sagittis, tempor posuere magna iaculis. Morbi commodo lorem nec imperdiet feugiat. Etiam elementum ligula ut lacus elementum, gravida finibus turpis sollicitudin. Etiam quis urna purus. Praesent a purus quam. In malesuada diam vel risus ultrices vehicula. Nam tincidunt nisi placerat, condimentum magna nec, condimentum lectus. Proin eget tellus hendrerit, finibus dui id, dapibus orci. Duis hendrerit lectus sit amet ante semper, nec consequat nunc ornare.

                Donec lobortis lacus quis mollis venenatis. Praesent vulputate odio enim, vel laoreet libero lacinia sit amet. Phasellus fermentum orci eros, non laoreet leo scelerisque quis. Quisque ac turpis fermentum, congue arcu vitae, consequat mi. Nulla elementum felis vel magna blandit posuere. Cras ligula nibh, lacinia et tellus vel, venenatis egestas sapien. Praesent venenatis enim felis, ut rhoncus erat tincidunt quis. Nam cursus aliquet mauris, in euismod libero convallis porttitor. Sed facilisis feugiat magna, vel iaculis nulla maximus in. Morbi vel massa urna. Nam ipsum nulla, pharetra sit amet sollicitudin mattis, tincidunt in orci.

                Cras et pretium ante, eget suscipit ipsum. Curabitur lobortis eget eros id tempor. In varius interdum magna, sed bibendum libero dapibus sed. Morbi pellentesque sit amet lacus vel blandit. Vivamus efficitur est sodales tellus volutpat, at egestas augue consequat. Nunc in turpis massa. Ut vel eros et nulla dignissim malesuada sit amet vitae mi. Nam cursus pharetra ultrices. Nunc laoreet ligula felis, vel tristique quam auctor ac. Aenean non urna sit amet libero rutrum rhoncus id feugiat urna. Donec cursus sem id scelerisque imperdiet. Nam sit amet sem sit amet nisl condimentum blandit id id erat. Phasellus dapibus lorem sit amet sapien placerat molestie sed quis neque. In velit orci, viverra nec tellus sed, vehicula feugiat tortor. Suspendisse sed luctus lorem.

                Ut eu pellentesque turpis, vitae tempus mauris. Phasellus ut sem vitae mi pharetra dignissim. Etiam maximus tincidunt interdum. Morbi sodales pulvinar nulla, ac tincidunt magna molestie id. Donec blandit ornare justo lobortis feugiat. Maecenas euismod aliquam eleifend. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas a ligula eu augue vulputate consequat eu a ipsum. Etiam in orci nec libero rhoncus varius cursus interdum ex. Duis non auctor odio. Curabitur sed magna et magna posuere varius. Etiam pretium vulputate est congue aliquet. In libero nibh, laoreet sed metus at, rhoncus pellentesque lectus. Curabitur eu ex venenatis, dictum ligula vitae, facilisis tortor. Praesent feugiat malesuada tincidunt. Proin sed cursus sapien, eget ullamcorper ante.

                Praesent odio enim, aliquam quis molestie sit amet, euismod id sem. Proin nec ligula elementum nibh consectetur pellentesque vitae volutpat purus. Integer pharetra ligula nec nibh venenatis gravida. Suspendisse ac tincidunt nibh. Suspendisse potenti. Vivamus faucibus dui nec leo interdum, ut bibendum diam egestas. Donec quis erat a purus gravida condimentum. Vivamus lobortis magna eu metus interdum, quis tempor nibh scelerisque. Curabitur ac tellus dapibus, porta metus semper, feugiat urna.
              </p>
            </div>

            <div class="row" id="lcsRow">
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                  <i class="fa fa-thumbs-up" style="float: left; margin: 2px 5px 0 0;"></i>
                  <p style="margin: 0;">Like</p>
              </div>
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                  <i class="fa fa-share" style="float: left; margin: 2px 5px 0 0;"></i>
                  <p style="margin: 0;">Share</p>
              </div>
            </div>

            <!-- <div class="row" id="likeRow">
              <a href="#">Likes go here</a>, <a href="#">here</a> liked this
            </div> -->

            <div class="row" id="postedComments">
              <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
                <img src="https://scontent-lga3-1.xx.fbcdn.net/hphotos-xfa1/v/t1.0-9/11903854_10204926490586041_3518895085011382707_n.jpg?oh=74f07a922533f077ca413e44bbe39f7c&oe=57457CB8" alt="" />
              </div>
              <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9">
                <div class="row" >
                  <p>
                    <a href="#">Donat Vucetaj</a> This is my comment
                  </p>
                </div>
                <div class="row" id="dateText">
                  DATE GOES HERE
                </div>
              </div>
            </div>
            <div class="row" id="postedComments">
              <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
                <img src="https://scontent-lga3-1.xx.fbcdn.net/hphotos-xfa1/v/t1.0-9/11903854_10204926490586041_3518895085011382707_n.jpg?oh=74f07a922533f077ca413e44bbe39f7c&oe=57457CB8" alt="" />
              </div>
              <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9">
                <div class="row" >
                  <p>
                    <a href="#">Donat Vucetaj</a> This is another test
                  </p>
                </div>
                <div class="row" id="dateText">
                  DATE GOES HERE
                </div>
              </div>
            </div>

            <div class="row" id="commentBox">

              <div class="form-group"id="textRow">
                <div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
                  <img src="https://scontent-lga3-1.xx.fbcdn.net/hphotos-xfa1/v/t1.0-9/11903854_10204926490586041_3518895085011382707_n.jpg?oh=74f07a922533f077ca413e44bbe39f7c&oe=57457CB8" alt="" />
                </div>
                <div class="col-xs-9 col-sm-9 col-md-10 col-lg-10">
                  <label for="comment"></label>
                  <textarea name="form-control" rows="2" columns="25" id="comment" placeholder="Add your comment..."></textarea>
                </div>
              </div>

            </div>




          </div><!--COL 4-->
        </div><!--MAIN ROW-->


      </div><!--MODAL BODY-->
    </div><!--MODAL CONTENT-->

  </div><!--MODAL DIALOG-->
</div><!--MODAL-->

</div><!--CONTAINER-->


<?php include( 'footer.php' ); ?>
