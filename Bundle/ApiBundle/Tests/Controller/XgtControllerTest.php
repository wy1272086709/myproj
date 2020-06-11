<?php
// +--------------------------------------------------------------------------
// | ProjectName :t8t-tbt-api
// +--------------------------------------------------------------------------
// | Description :XgtControllerTestTest.php
//+--------------------------------------------------------------------------
// | Author: xiaoyuqin 
// +--------------------------------------------------------------------------
// | Version:0.0.1                Date:2018/12/7 09:25
// +--------------------------------------------------------------------------


namespace ApiBundle\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class XgtControllerTest extends WebTestCase
{

    public function searchDataProvider()
    {
        $nothing = [];
        $source_search = [
            'source' => 21,
            'page' => 1,
            'perPage' => 100,
        ];
        $visible_search = [
            'visible' => 3,
        ];
        $zoneid_search = [
            'zoneid' => 14,
        ];
        $comment_app_search = [
            'commentApp' => 1,
        ];
        $recomemnd_search = [
            'commentApp' => 4,
        ];
        $common_date_recommend_search = [
            'commentApp' => 5,
        ];
        $category_search = [
            'category' => 0,
        ];
        $styleid_search = [
            'styleid' => 22,
        ];
        $partid_search = [
            'partid' => 1,
        ];
        $all = array_merge(
            $nothing,
            $source_search,
            $visible_search,
            $zoneid_search,
            $comment_app_search,
            $recomemnd_search,
            $common_date_recommend_search,
            $category_search,
            $styleid_search,
            $partid_search
        );

        $condition = [
            'nothing' => [$nothing],
            'source_search' => [$source_search],
            'visible_search' => [$visible_search],
            'zoneid_search' => [$zoneid_search],
            'comment_app_search' => [$comment_app_search],
            'recomemnd_search' => [$recomemnd_search],
            'common_date_recommend_search' => [$comment_app_search],
            'category_search' => [$category_search],
            'styleid_search' => [$styleid_search],
            'partid_search' => [$partid_search],
            'all' => [$all],
        ];

        return $condition;
    }

    /**
     * @dataProvider searchDataProvider
     * @param $params
     */
    public function testSearch($params)
    {
        $client = static::createClient();
        $client->request('GET', '/xgt/search', $params);
        $content = $client->getResponse()->getContent();
        $decode = json_decode($content, true);
        $errCode = $decode['errorCode'] ?? 1;
        $data = $decode['data'] ?? [];
        $this->assertEquals(0, $errCode);
        $this->assertGreaterThanOrEqual(0, count($data));
    }

}