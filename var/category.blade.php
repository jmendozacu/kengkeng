@extends($package_name .'::layout.main')

@section('content')
        <!--  -->
<!-- Categories -->
<div class="container-fluid" id="cat-banner" style="background-image: url({{ asset($category->getBanner()) }})">
    <div class="container">
        <div class="div-ba">
            <a href="#" class="big-a">
                {{ $category->name }}
            </a>
        </div>
        <div class="clearfix"></div>
        <ol class="breadcrumb">
            <li><a href="#">Chuyên mục</a></li>
            <li class="active">{{ $category->name }}</li>
        </ol>
    </div>
</div>
<!--  -->
<div class="container-fluid" id="cat-cont">
    <!--  -->
    <div class="container cat-top">
        <div class="col-xs-12 select-cat">
            <form class="form-inline">
                <div class="form-group">

                    <div class="input-group">
                        <div class="input-group-addon">Đăng:</div>
                        <select class="form-control">
                            <option>Tat ca</option>
                            <option>Tat ca</option>
                            <option>Tat ca</option>
                            <option>Tat ca</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xs-12 select-cat">
            <form class="form-inline">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">Xếp theo:</div>
                        <select class="form-control">
                            <option>Đánh giá</option>
                            <option>Đánh giá</option>
                            <option>Đánh giá</option>
                            <option>Đánh giá</option>
                            <option>Đánh giá</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-xs-12 canh-bao">
            <i class="fa fa-bell" aria-hidden="true"></i> Cảnh báo vé máy bay
        </div>

        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 pull-right cat-tright">
            <a href="{{ route('front.classified.create') }}">đăng tin ngay</a>
        </div>
    </div>

    <!--  -->

    <div class="container cat-contite" id="content-news">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            @foreach($classifieds as $classified)
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 item-cont">
                        <h4><a href="{{ route('front.classified.show', $classified->slug) }}">{{ $classified->name }}</a></h4>
                        <img src="{{ $classified->getImage('thumbnail') }}">
                        <div class="ritem">
                            <p class="price">{{ $classified->getPrice() }} </p>
                            <p class="status-item">{{ $classified->categories->first()->name }}</p>
                            <p class="more-item"><a href="{{ route('front.classified.show', $classified->slug) }}">Chi tiết >></a></p>

                            <div class="rating">
                                <span>☆</span><span>☆</span><span>☆</span><span>☆</span><span>☆</span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="bitem">
                            <i class="fa fa-clock-o"></i> {{ $classified->created_at->diffForHumans() }} <i class="fa fa-eye"></i> {{ $classified->view_counter }} lượt xem <i class="fa fa-phone"></i> {{ $classified->mobile }}
                        </div>
                    </div>
                </div>
            @endforeach

            <nav style="text-align: center; display: block; clear: both; width: 100%;">
                {!! $classifieds->links() !!}
            </nav>
        </div>

        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 cat-rightbar">
            <img src="http://i.imgur.com/kA46iLF.jpg" class="thumbnail">

            <img src="https://www.vietnamairlines.com/~/media/Images/Home%20Banner/NewCI-Anh.ashx?h=350&la=en&w=450" class="thumbnail">

            <img src="https://i.ytimg.com/vi/zG_Q50JDeLo/maxresdefault.jpg" class="thumbnail">
        </div>
    </div>
</div>
@endsection
