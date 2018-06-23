<?php

class Log_Driver_File
{

    protected $config = array(
        'log_time_format' => 'Y-m-d H:i:s',
        'log_file_size'   => 1073741824,
        'log_path'        => '',
    );

    // 实例化并传入参数
    public function __construct($config = array())
    {
        $this->config['log_path'] = Util_Conf::get('log.path');
        $this->config             = array_merge($this->config, $config);
    }

    /**
     * 日志写入接口
     * @access public
     * @param string $log 日志信息
     * @param string $destination 写入目标
     * @throws Exception
     * @return void
     */
    public function write($log, $destination = '')
    {
        $now = date($this->config['log_time_format']);
        if (empty($destination)) {
            $destination = date('y_m_d') . '.log';
        }

        $destination = $this->config['log_path'] . $destination;
        $path        = dirname($destination);
        if (!is_dir($path) && !@mkdir($path, 0777, true)) {
            throw new Exception('创建日志目录失败', 2);
        }

        // 检测日志文件大小，超过配置大小则备份日志文件重新生成
        clearstatcache();
        if (
            file_exists($destination) &&
            is_writable($destination) &&
            is_writable(dirname($destination)) &&
            floor($this->config['log_file_size']) <= filesize($destination)
        ) {
            try {
                @rename(
                    $destination,
                    dirname($destination) . '/' . time() . '-' . basename($destination)
                );
            } catch (Exception $e) {
                @Log::warn('尝试重命名｛' . $destination . '｝失败，请检查文件和文件夹权限', true, true);
            }
        }
        clearstatcache();
        if (!isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $_SERVER['HTTP_X_FORWARDED_FOR'] = $_SERVER['REMOTE_ADDR'];
        }

        error_log(
            "[{$now}] " . $_SERVER['HTTP_X_FORWARDED_FOR'] . ' ' . $_SERVER['REQUEST_URI'] . "\r\n{$log}\r\n",
            3,
            $destination
        );

        // 防止在 Linux 跑 Crontab 任务时，指定 root 用户创建日志，这样 nginx 所属的用户就写不进去日志了
        if (PHP_OS != 'WINNT') {
            $pid = posix_getpwuid(posix_geteuid());
            if (strtolower($pid['name']) == 'root') {
                @chown($destination, Util_Conf::get('log.web_server.user'));
                @chgrp($destination, Util_Conf::get('log.web_server.group'));
            }
        }
    }
}
