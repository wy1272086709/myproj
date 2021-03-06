<?php
/**
 * Created by PhpStorm.
 * User: reed.chen
 * Date: 2017/9/15
 * Time: 15:19
 */

$category['zoneTypes'] = [
    1=>'客厅',2=>'卧室',3=>'餐厅',
    4=>'厨房', 5=>'卫生间',6=>'阳台',
    7=>'书房',8=>'玄关',9=>'过道',
    10=>'儿童房',11=>'衣帽间',12=>'花园',//13=>'休闲区'
];
$category['zoneTypes']['small_zonetype'] = [
    1 => [
        //1=>'小户型客厅',
        2=>'2014客厅',
        //3=>'简约客厅',
        //4=>'中式客厅',
    ],
    2 => [
        //5=>'小卧室',
        6=>'婚房卧室',
        //7=>'欧式卧室',
        8=>'女生卧室',
        9=>'2014卧室',
    ],
    3 => [
        //10=>'中式餐厅',
        //11=>'小餐厅',
        //12=>'欧式餐厅',
        13=>'客厅餐厅',
    ],
    4 => [
        14=>'开放式厨房',
        //15=>'欧式厨房',
        //16=>'小户型厨房',
    ],
    5 => [
        //17=>'小卫生间',
        //18=>'欧式卫生间',
    ],
    6 => [
        19=>'客厅阳台',
        20=>'卧室阳台',
        21=>'露天阳台',
    ],
    7 => [
        //22=>'小书房',
        23=>'儿童书房',
        //24=>'欧式书房',
        //25=>'中式书房',
    ],
    8 => [
        26=>'客厅玄关',
        27=>'进门玄关',
        28=>'卧室玄关',
    ]
];
$category['zoneTypes']['categoryName'] = 'space';

$category['homeTypes'] = [
    0=>[
        1=>'小户型',
        7=>'一居',
        2=>'二居',
        3=>'三居',
        4=>'四居',
        5=>'复式',
        6=>'别墅',
        8=>'公寓',
        9=>'loft',
    ],
    1=>[
        1=>'办公空间',
        2=>'商业空间',   //原商场
        3=>'专卖店/商铺',
        4=>'酒店宾馆',
        5=>'餐饮酒吧',
        6=>'娱乐空间',
        7=>'桑拿会所',  //原休闲健身
        8=>'体育场管',
        9=>'展览展示',
        10=>'文化科研',  //原博物馆
        11=>'图书馆',
        12=>'学校',
        13=>'医院设计',
        14=>'机场车船站',
        15=>'工厂',
        16=>'公园广场',
        17=>'会所',
        18=>'园林景观',
        19=>'售楼中心',
        20=>'常见公装'
    ],
    2=>[
        82=>'美容院',
        30=>'酒店',
        116=>'ktv',
        43=>'酒吧',
        76=>'美发',
        51=>'写字楼',
        53=>'办公室',
        31=>'宾馆',
        217=>'会所',
        41=>'咖啡厅',
        3=>'商铺',
        75=>'服装店',
        215=>'厂房',
        150=>'医院',
        211=>'图书馆',
        212=>'幼儿园',
        357=>'广场',
        356=>'公园',
        358=>'会议室',
        127=>'体育馆',
        /*  359=>'其它',  */
        90 => '园林',
        91 =>'影院',
        92 =>'展厅',
        93 =>'博物馆',
        94 =>'汗蒸房',
        95 =>'商场',
        96 =>'酒楼',
        97 =>'茶馆',
        98 =>'健身房',
        99 =>'超市',
        100 =>'美容店',
        101 =>'奶茶店',
        102 =>'商业街',
        103 =>'蛋糕店',
        104 =>'快餐店',
        105 =>'渔具店',
        106 =>'溜冰场',
        107 =>'火锅店',
        108 =>'面馆',
        109 =>'酒庄',
        110 =>'精品店',
        111 =>'花店',
        112 =>'祠堂',
        113 =>'鞋店',
        114 =>'影楼',
        115 =>'冷饮店',
        118 =>'内衣店',
        117 =>'眼镜店'
    ]
];
$category['homeTypes'][0]['categoryName'] ='hometype';
$category['homeTypes'][2]['categoryName'] ='public';

$category['homeTypes']['small_hometype'] = [
    0=>[
        1=>[
            1=>'20平米小户型',
            2=>'30平米小户型',
            3=>'40平米小户型',
            4=>'50平米小户型',
            5=>'60平米小户型',
            6=>'70平米小户型',
            7=>'80平米小户型'
        ],
        2=>[
            8=>'两室一厅',
            9=>'两室两厅',
            10=>'90平米',
            22=>'100平米',
            23=>'50平米两室一厅',
            24=>'60平米两室一厅',
            25=>'70平米两室一厅',
            26=>'80平米两室一厅',
        ],
        3=>[
            11=>'三室一厅',
            12=>'三室两厅',
            13=>'120平米装修'
        ],
        4=>[
            14=>'四室一厅',
            15=>'四室两厅'
        ],
        5=>[
            //16=>'复式楼梯',
            //17=>'复式客厅',
            //18=>'复式卧室'
        ],
        6=>[
            19=>'豪华别墅',
            20=>'农村别墅',
            //21=>'欧式别墅'
        ]
    ]
];


//案例风格
$category['styleIds'] = [
    13 => '简约',
    15 => '现代',
    4 => '中式',
    2 => '欧式',
    9 => '美式',
    11 => '田园',
    6 => '新古典',
    0 => '不限',
    12 => '地中海',
    8 => '东南亚',
    // 16  => '创意',
    17 =>'日式',
    18 => '宜家',
    19 =>'北欧',
    20 => '简欧',
    21 => '巴洛克',
    22 => '复古',
    100 => '混搭'
];
$category['styleIds']['categoryName'] = 'style';

/*效果图筛选子页面关键字数组-局部分类*/
$category['partTypes'] = [
    336 => '背景墙', 16 => '吊顶', 14 => '隔断', 9 => '窗帘',
    340 => '飘窗', 33 => '榻榻米', 17 =>'橱柜', 343 => '博古架',
    333 => '阁楼', 249=>'隐形门', 21=>'吧台', 22=>'酒柜',
    23=>'鞋柜', 24=>'衣柜', 19=>'窗户',/*,15=>'楼梯'*/
    20=>'相片墙', 18 => '楼梯', 40 => '窗台', 41 => '罗马柱',
    42 => '垭口', 43 => '壁橱', 44 => '门口/大门', 45 => '落地窗', 359 => '其它',
];
$category['partTypes']['categoryName'] = 'part';


//面积区间
$category['areas'] = [
    1=>'60㎡以下', 2=>'60-80㎡', 3=>'80-100㎡',
    4=>'100-120㎡', 5=>'120-150㎡', 6=>'150㎡以上'
];

//颜色风格色值表
$category['colors'] = [
    1=>  [ 'name'=>'白色', 'value'=>'#f6f8f8'],
    2=>  [ 'name'=>'黑色', 'value'=>'#111111'],
    3=>  [ 'name'=>'红色', 'value'=>'#df394c'],
    4=>  [ 'name'=>'黄色', 'value'=>'#EED047'],
    5=>  [ 'name'=>'绿色', 'value'=>'#bfd244'],
    6=>  [ 'name'=>'橙色', 'value'=>'#f0924b'],
    7=>  [ 'name'=>'粉色', 'value'=>'#f17e94'],
    8=>  [ 'name'=>'蓝色', 'value'=>'#689cd2'],
    9=>  [ 'name'=>'灰色', 'value'=>'#999999'],
    10=> [ 'name'=>'紫色', 'value'=>'#8d6dac'],
    11=> [ 'name'=>'棕色', 'value'=>''],
    12=> [ 'name'=>'米色', 'value'=>'#EDDDCE'],
    13=> [ 'name'=>'彩色', 'value'=>''],
    14=> [ 'name'=>'咖啡色', 'value'=>'#AA7F52']
];
$category['goujian'] = array(
    336  => '背景墙',
    16   => '吊顶',
    14   => '隔断',
    9    => '窗帘',
    340  => '飘窗',
    33   => '榻榻米',
    17   => '橱柜',
    343  => '博古架',
    333  => '阁楼',
    249  => '隐形门',
    21   => '吧台',
    22   => '酒柜',
    23   => '鞋柜',
    24   => '衣柜',
    19   => '窗户',
    //15   => '楼梯',
    20   => '相片墙',
    18   => '楼梯',
    40   => '窗台',
    41   => '罗马柱',
    42   => '垭口',
    43   => '壁橱',
    44   => '门口/大门',
    45   => '落地窗',
    359  => '其它',
);

$category['hometype'] =array(
    0 => array(
        1=>'小户型',
        7=>'一居',
        2=>'二居',
        3=>'三居',
        4=>'四居',
        5=>'复式',
        6=>'别墅',
        8=>'公寓',
        9=>'loft',
    ),
    1 => array(1=>'办公空间',
        2=>'商业空间',   //原商场
        3=>'专卖店/商铺',
        4=>'酒店宾馆',
        5=>'餐饮酒吧',
        6=>'娱乐空间',
        7=>'桑拿会所',  //原休闲健身
        8=>'体育场管',
        9=>'展览展示',
        10=>'文化科研',  //原博物馆
        11=>'图书馆',
        12=>'学校',
        13=>'医院设计',
        14=>'机场车船站',
        15=>'工厂',
        16=>'公园广场',
        17=>'会所',
        18=>'园林景观',
        19=>'售楼中心',
        20=>'常见公装'
    ),
    2 => array(
        82=>'美容院',
        30=>'酒店',
        116=>'ktv',
        43=>'酒吧',
        76=>'美发',
        51=>'写字楼',
        53=>'办公室',
        31=>'宾馆',
        217=>'会所',
        41=>'咖啡厅',
        3=>'商铺',
        75=>'服装店',
        215=>'厂房',
        150=>'医院',
        211=>'图书馆',
        212=>'幼儿园',
        357=>'广场',
        356=>'公园',
        358=>'会议室',
        127=>'体育馆',
        //359=>'其它',
        90 => '园林',
        91 =>'影院',
        92 =>'展厅',
        93 =>'博物馆',
        94 =>'汗蒸房',
        95 =>'商场',
        96 =>'酒楼',
        97 =>'茶馆',
        98 =>'健身房',
        99 =>'超市',
        100 =>'美容店',
        101 =>'奶茶店',
        102 =>'商业街',
        103 =>'蛋糕店',
        104 =>'快餐店',
        105 =>'渔具店',
        106 =>'溜冰场',
        107 =>'火锅店',
        108 =>'面馆',
        109 =>'酒庄',
        110 =>'精品店',
        111 =>'花店',
        112 =>'祠堂',
        113 =>'鞋店',
        114 =>'影楼',
        115 =>'冷饮店',
        118 =>'内衣店',
        117 =>'眼镜店'
    )
);
$category['goodcase']['zonetype'] = array(			//4 00
    1 => '客厅',									//4 01
    2 => '卧室', 								//4 02
    3 => '餐厅', 								//4 03
    4 => '厨房', 								//4 04
    5 => '卫生间', 								//4 05
    6 => '阳台', 								//4 06
    7 => '书房', 								//4 07
    8 => '玄关', 								//4 08
    //9 => '过道', 								//4 09
    10 => '儿童房', 								//4 10
    //11 => '衣帽间', 								//4 11
    //12 => '花园', 								//4 12
    //13 => '休闲区'                //4 13
);
$category['styleid'] = array(
    13 => '简约',
    15 => '现代',
    4 => '中式',
    2 => '欧式',
    9 => '美式',
    11 => '田园',
    6 => '新古典',
    0 => '不限',
    12 => '地中海',
    8 => '东南亚',
    // 16  => '创意',
    17 =>'日式',
    18 => '宜家',
    19 =>'北欧',
    20 => '简欧',
    21 => '巴洛克',
    22 => '复古',
    100 => '混搭'
);

$category['area']=array(
    1=>'60㎡以下',
    2=>'60-80㎡',
    3=>'80-100㎡',
    4=>'100-120㎡',
    5=>'120-150㎡',
    6=>'150㎡以上'
);
$category['color_value']=array(
    '1'=>array('name'=>'白色','value'=>'#f6f8f8'),
    '12'=>array('name'=>'米色','value'=>'#EDDDCE'),
    '4'=>array('name'=>'黄色','value'=>'#EED047'),
    '6'=>array('name'=>'橙色','value'=>'#f0924b'),
    '3'=>array('name'=>'红色','value'=>'#df394c'),
    '7'=>array('name'=>'粉色','value'=>'#f17e94'),
    '5'=>array('name'=>'绿色','value'=>'#bfd244'),
    '8'=>array('name'=>'蓝色','value'=>'#689cd2'),
    '9'=>array('name'=>'灰色','value'=>'#999999'),
    '2'=>array('name'=>'黑色','value'=>'#111111'),
    '10'=>array('name'=>'紫色','value'=>'#8d6dac'),
    '13'=>array('name'=>'彩色','value'=>''),
    '14'=>array('name'=>'咖啡色','value'=>'#AA7F52')
);

$category['opriceid'] = array(
    1 => '3万以下',
    2 => '3-5万',
    3 => '5-8万',
    4 => '8-12万',
    5 => '12-18万',
    6 => '18-30万',
    7 => '30-100万',
    8 => '100万以上'
);

return $category;
