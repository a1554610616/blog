<?php

namespace App\Http\Controllers\Home;

use App\Http\Model\Article;
use App\Http\Model\Category;
use App\Http\Model\Links;
//use App\Http\Requests\Request;
use Illuminate\Http\Request;
use PDF;
use App\Jobs\test;
use QrCode;
class IndexController extends CommonController
{
    public function index()
    {
        //点击量最高的6篇文章（站长推荐）
        $pics = Article::orderBy('art_view','desc')->take(6)->get();

        //图文列表5篇（带分页）
        $data = Article::orderBy('art_time','desc')->paginate(5);

        //友情链接
        $links = Links::orderBy('link_order','asc')->get();

        return view('home.index',compact('pics','data','links'));
    }

    public function cate($cate_id)
    {
        //图文列表4篇（带分页）
        $data = Article::where('cate_id',$cate_id)->orderBy('art_time','desc')->paginate(4);

        //查看次数自增
        Category::where('cate_id',$cate_id)->increment('cate_view');

        //当前分类的子分类
        $submenu = Category::where('cate_pid',$cate_id)->get();

        $field = Category::find($cate_id);
        return view('home.list',compact('field','data','submenu'));
    }

    public function article($art_id)
    {
        dd($art_id);
        $field = Article::Join('category','article.cate_id','=','category.cate_id')->where('art_id',$art_id)->first();

        //查看次数自增
        Article::where('art_id',$art_id)->increment('art_view');

        $article['pre'] = Article::where('art_id','<',$art_id)->orderBy('art_id','desc')->first();
        $article['next'] = Article::where('art_id','>',$art_id)->orderBy('art_id','asc')->first();

        $data = Article::where('cate_id',$field->cate_id)->orderBy('art_id','desc')->take(6)->get();

        return view('home.new',compact('field','article','data'));
    }

    /**
     * 测试web页面展示pdf，使用loadHTML()方法加载
     * @return mixed
     */
    public function testStreamPdf()
    {
        $html = '<html><head><title>Laravel</title><meta http-equiv=\'Content-Type\' content=\'text/html; charset=utf-8\'/><style>body{  font-family: \'msyh\';  }  @font-face {  font-family: \'msyh\';  font-style: normal;  font-weight: normal;  src: url(http://www.testenv.com/fonts/msyh.ttf) format(\'truetype\');  }</style></head><body><div class=\'container\'><div class=\'content\'><p style=\'font-family: msyh, DejaVu Sans,sans-serif;\'>献给母亲的爱</p><div style=\'font-family: msyh, DejaVu Sans,sans-serif;\' class=\'title\'>Laravel 5中文测试</div><div  class=\'title\'>测试三</div></div></div></body></html>';
        $pdf = PDF::loadHTML($html);
        return $pdf->download();
    }

    /**
     * 测试下载pdf，使用loadView()方法
     * @return mixed
     */
    public function testDownloadPdf()
    {
        $data = array('name'=>'测试');
        return view('invoice',$data);
        $pdf = PDF::loadView('invoice', $data);
        return $pdf->stream('invoice.pdf');
    }

    public function savePic(Request $request)
    {
        $action=$request->input('action','');
        if($action=='save'){
            $picInfo = $request->input('baseimg');

            $streamFileRand = date('YmdHis').rand(1000,9999); //图片名
            $picType ='.png';//图片后缀

            $streamFilename = "./public/echarts/".$streamFileRand .$picType; //图片保存地址
            preg_match('/(?<=base64,)[\S|\s]+/',$picInfo,$picInfoW);//处理base64文本
            //print_r($picInfoW);die;
            file_put_contents($streamFilename,base64_decode($picInfoW[0]));//文件写入

            echo json_encode(['code'=>1,'info'=>$streamFileRand .$picType]);
        }
    }

    public function test(){
        $job = (new test(111))->onQueue('testQueue');
        $this->dispatch($job);
        echo 123;die;
    }

    public function qrCode()
    {
        return QrCode::encoding('UTF-8')
            ->size(380)
            ->merge('/public/test.png',.15)
            ->generate('你好，Laravel学院！');
    }
}