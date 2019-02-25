<?php

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * 获取数据源
     * @Auth: kingofzihua
     * @param $url
     * @return mixed
     */
    public function get_data_by_path($url)
    {
        $baseUrl = base_path('tests/Data/');

        $data = file_get_contents($baseUrl . $url);

        return \GuzzleHttp\json_decode($data, true);
    }

    /**
     * 获取 apu地址
     * @Auth: kingofzihua
     * @param $url
     * @return string
     */
    public function get_url_by_path($url)
    {
        $baseUrl = config('app.url') . '/api/weixin/';

        return $baseUrl . $url;
    }

    /**
     * @Auth: kingofzihua
     * @param $url
     * @param $data_url
     * @return $this
     */
    public function post_data($url)
    {
        $path = $this->get_url_by_path($url);

        $header = [
            'Accept' => 'application/x.daguoyi.wxv1+json',
        ];

        $data = $this->get_data_by_path($url . '.json');

        return $this->post($path, $data, $header);
    }

    /**
     * @Auth: kingofzihua
     * @param $url
     * @return $this
     */
    public function get_url($url)
    {
        $path = $this->get_url_by_path($url);

        $header = [
            'Accept' => 'application/x.daguoyi.wxv1+json',
        ];

        return $this->get($path, $header);
    }

}
