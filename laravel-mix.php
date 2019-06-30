<?php

/*
 * Credit: https://www.sitepoint.com/use-laravel-mix-non-laravel-projects/
 */

if (!function_exists('mix')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param string $path
     * @param string $manifestDirectory
     * @param string $format
     * @return string
     *
     * @throws \Exception
     */
    function mix($path, $manifestDirectory = '', $format = 'url')
    {
        static $manifest;
        $publicFolder = '/dist';
        $rootPath = get_stylesheet_directory();
        $publicPath = $rootPath . $publicFolder;
        if ($manifestDirectory && substr($manifestDirectory, 0, 1) != '/') {
            $manifestDirectory = "/{$manifestDirectory}";
        }

        if (!$manifest) {
            if (!file_exists($manifestPath = ($publicPath . $manifestDirectory . '/mix-manifest.json'))) {
                throw new Exception('The Mix manifest does not exist.');
            }
            $manifest = json_decode(file_get_contents($manifestPath), true);
        }

        if (substr($path, 0, 1) != '/') {
            $path = "/{$path}";
        }

        if (!array_key_exists($path, $manifest)) {
            throw new Exception(
                "Unable to locate Mix file: {$path}. Please check your " .
                'webpack.mix.js output paths and try again.'
            );
        }

        $path_prefix = $publicPath;

        if ($format == 'url') {
            $path_prefix = get_stylesheet_directory_uri().$publicFolder;
        } elseif ($format == 'absolute') {
            $path_prefix = get_stylesheet_directory();
        } elseif ($path_prefix === '' || $path_prefix === false) {
            $path_prefix = '';
        }

        return $path_prefix . $manifest[$path];
    }
}