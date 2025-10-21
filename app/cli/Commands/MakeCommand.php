<?php

namespace App\Cli\Commands;

use App\Cli\CommandInterface;

class MakeCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'make';
    }

    public function getDescription(): string
    {
        return 'Generate files (controller, model)';
    }

    public function execute(array $args): int
    {
        $type = $args[0] ?? '';
        $name = $args[1] ?? '';
        
        if (!$type || !$name) {
            echo "\033[31mUsage: php console make <type> <name>\033[0m\n";
            echo "Types: controller, model\n";
            return 1;
        }
        
        switch ($type) {
            case 'controller':
                return $this->makeController($name);
            case 'model':
                return $this->makeModel($name);
            default:
                echo "\033[31mUnknown type: {$type}\033[0m\n";
                return 1;
        }
    }

    private function makeController(string $name): int
    {
        $className = ucfirst($name) . 'Controller';
        $filename = APP_PATH . "/controllers/{$className}.php";
        
        if (file_exists($filename)) {
            echo "\033[31mController already exists: {$className}\033[0m\n";
            return 1;
        }
        
        $template = "<?php

namespace App\\Controllers;

class {$className}
{
    public function index()
    {
        // Implementation here
    }
    
    public function show(\$id)
    {
        // Implementation here
    }
    
    public function store()
    {
        // Implementation here
    }
    
    public function update(\$id)
    {
        // Implementation here
    }
    
    public function destroy(\$id)
    {
        // Implementation here
    }
}";
        
        file_put_contents($filename, $template);
        echo "\033[32mController created: {$className}\033[0m\n";
        return 0;
    }

    private function makeModel(string $name): int
    {
        $className = ucfirst($name);
        $filename = APP_PATH . "/models/{$className}.php";
        
        if (file_exists($filename)) {
            echo "\033[31mModel already exists: {$className}\033[0m\n";
            return 1;
        }
        
        $template = "<?php

namespace App\\Models;

use App\\Core\\Model;

class {$className} extends Model
{
    protected \$table = '" . strtolower($name) . "s';
    protected \$fillable = [];
}";
        
        file_put_contents($filename, $template);
        echo "\033[32mModel created: {$className}\033[0m\n";
        return 0;
    }
}