<?php

namespace Adyns\ViewMaker;

use Illuminate\Console\Command;

class ViewMaker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view 
                                {name : The name of the view you want to create} 
                                {--e|extend=default : The name of the view you want to extend}
                                {--c|css : Whether to include the css in the public asset css folder or one}
                                {--j|js : Whether to include the js in the public asset js folder or one}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is to generate views for your laravel application';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        define('//', DIRECTORY_SEPARATOR);
        chdir('.//resources//views//');
        $viewFile = trim($this->argument('name'));
        $css_temp = "";
        $js_temp = "";
        $file_text =  <<<_END
            <!DOCTYPE html>
            <html lang="{{  str_replace('_', '-', app()->getLocale()) }}">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">

                <!-- CSRF Token -->
                <meta name="csrf-token" content="{{ csrf_token() }}">

                <title>{{ config('app.name', 'Laravel') }}</title>
                
                {$css_temp}
                
            </head>
            <body>

                {$js_temp}
            </body>
            </html>
            _END;
        $extends = trim($this->option('extend'));

        if($this->option('css')){
            if($extends != 'default'){
                $this->info("you can't use extends with this feature");
                exit();
            }
            chdir(('..//..//public//'));
            foreach ( $this->findAllFiles('css') as $value){
                if((new \SplFileInfo($value))->getExtension() == 'css'){
                    $value = str_replace($value,"//","/");
                    $css_temp .= "<link href=\"{{asset('{$value}')}}\" rel=\"stylesheet\" /> \n";
                }
            }
            chdir('.//..//resources//views//');
        }

        if($this->option("js")){
            if($extends != 'default'){
                $this->info("you can't use extends with this feature");
                exit();
            }
            chdir(('..//..//public//'));
            foreach ( $this->findAllFiles('js') as $value){
                if((new \SplFileInfo($value))->getExtension() == 'js'){
                    $value = str_replace($value,"//","/");
                    $js_temp .= "<script src=\"{{asset('{$value}')}}\"></script>\n";
                }
            }
            chdir('.//..//resources//views//');
        }

        $file_text =  <<<_END
            <!DOCTYPE html>
            <html lang="{{  str_replace('_', '-', app()->getLocale()) }}">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">

                <!-- CSRF Token -->
                <meta name="csrf-token" content="{{ csrf_token() }}">

                <title>{{ config('app.name', 'Laravel') }}</title>
                
                {$css_temp}
            </head>
            <body>

                {$js_temp}
            </body>
            </html>
            _END;

        if($extends != 'default' ){
            $file_text = $this->extendFile($extends) ;
        }
        $viewName = $this->createBladeFolder($viewFile);
        $this->createBladeFile($viewName,$file_text);

    }

    private function findAllFiles($dir)
    {
        $root = array_diff(scandir($dir),['..','.']);
        foreach ($root as $value){
            if(is_file("{$dir}//{$value}")){
                $results[] = "{$dir}//{$value}";
                continue;
            }
            foreach ($this->findAllFiles("{$dir}//{$value}") as $value){
                $results[] = $value;
            }
        }

        return $results;
    }

    private function createBladeFile($viewName,$fileText)
    {
        if(file_exists( getcwd()."//". $viewName)){
            $this->error("view {$viewName} already exists");
            exit();
        }else{
            $create = fopen($viewName,'w');
            fwrite($create,$fileText);
            fclose($create);
            $this->info('the file was successfully created');
        }

    }

    private function createBladeFolder($viewFile)
    {
        $path = ".//";
        $viewName = $viewFile.'.blade.php';

        if(strpos($viewFile,'.')){
            $dirArray = explode('.',$viewFile);
            for($i = 0; $i< (count($dirArray)-1) ; $i++){
                $path .="{$dirArray[$i]}//";
            }
            if(file_exists($path)){
                chdir($path);
            }else{
                if(mkdir($path,0777,true)){
                    chdir($path);
                }
            }

            $viewName = $dirArray[count($dirArray)-1].'.blade.php';
        }

        return $viewName;
    }

    private function extendFile($extends)
    {
        $file_text = "";

        if(strpos($extends,"?")){
            $extendFile = explode("?",$extends)[0];
            $extendPath = explode("?",$extends)[1];

            $file_extend_path = strpos($extendFile,".") ?
                "./".implode("/",explode(".",$extendFile)).".blade.php" :
                $extendFile.".blade.php";

            if(file_exists($file_extend_path)){
                $file_text .= "@extends('{$extendFile}')\n\n";
                if(strpos($extendPath,",")){
                    foreach (explode(",",$extendPath) as $extend_content){
                        $file_text .= "@section('{$extend_content}')\n\n@endsection\n";
                    }
                }else{
                    $file_text .= "@section('{$extendPath}')\n\n@endsection\n";
                }
            }else{
                $this->error('The file to extend does not exist');
                exit("The file to extend does not exists");
            }
        }else{
            $this->error("Specify the section to extend");
        }
        return $file_text;
    }


}
