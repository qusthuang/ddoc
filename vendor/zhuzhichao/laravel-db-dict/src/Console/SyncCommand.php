<?php
namespace Zhuzhichao\LaravelDbDict\Console;

use DB;
use Illuminate\Console\Command;
use Zhuzhichao\LaravelDbDict\Models\DbTable;

class SyncCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:dict-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步数据表数据到数据字典';

    private $dbName;

    public function __construct()
    {
        $this->dbName = DB::getDatabaseName();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $tables = $this->syncTables();
        $this->syncColumns($tables);

    }

    public function syncTables()
    {
        $tableSql = "select table_name from (select t.table_schema as db_name,   
t.table_name,   
(case when t.table_type = 'BASE TABLE' then 'table' 
when t.table_type = 'VIEW' then 'view'  
else t.table_type   
end) as table_type, 
c.column_name,  
c.column_type,  
c.column_default,   
c.column_key,   
c.is_nullable,  
c.extra,    
c.column_comment    
from information_schema.tables as t 
inner join information_schema.columns as c  
on t.table_name = c.table_name  
and t.table_schema = c.table_schema 
where t.table_type in('base table', 'view') 
and t.table_schema = '{$this->dbName}'  
order by t.table_schema, t.table_name, c.ordinal_position) as all_columns group by table_name";

        $tableNames = array_pluck((array) DB::select($tableSql), 'table_name');
        $this->info("共有".count($tableNames)."张表, 将同步表信息");
        $bar = $this->output->createProgressBar(count($tableNames));
        foreach ($tableNames as $tableName) {
            $tableBuilder = DbTable::withTrashed()->where('name', $tableName);
            if ($tableBuilder->exists()) {
                $table = $tableBuilder->first();
                if ($table->trashed()) {
                    $table->restore();
                }
            } else {
                DbTable::create([
                    'name' => $tableName
                ]);
            }
            $bar->advance();
        }
        DbTable::whereNotIn('name', $tableNames)->delete();
        $bar->finish();

        return DbTable::all();
    }

    private function syncColumns($tables)
    {
        $bar = $this->output->createProgressBar(count($tables));
        /** @var DbTable $table */
        foreach ($tables as $table) {
            $columnSql = "select t.table_schema as db_name,   
t.table_name,   
(case when t.table_type = 'BASE TABLE' then 'table' 
when t.table_type = 'VIEW' then 'view'  
else t.table_type   
end) as table_type, 
c.column_name,  
c.column_type,  
c.column_default,   
c.column_key,   
c.is_nullable,  
c.extra,    
c.column_comment    
from information_schema.tables as t 
inner join information_schema.columns as c  
on t.table_name = c.table_name  
and t.table_schema = c.table_schema 
where t.table_type in('base table', 'view') 
and t.table_schema = '{$this->dbName}'
and t.table_name = '{$table->name}'
order by t.table_schema, t.table_name, c.ordinal_position";

            $columnInfos = DB::select($columnSql);
            foreach ($columnInfos as $columnInfo) {
                $columnBuilder = $table->columns()->withTrashed()->where('name', $columnInfo->column_name);
                if ($columnBuilder->exists()) {
                    $column = $columnBuilder->first();
                    if ($column->trashed()) {
                        $column->restore();
                    }
                    $table->columns()->where('name', $columnInfo->column_name)->update([
                        'name'        => $columnInfo->column_name,
                        'type'        => $columnInfo->column_type,
                        'default'     => $columnInfo->column_default,
                        'key'         => $columnInfo->column_key,
                        'is_nullable' => $columnInfo->is_nullable == 'YES' ? true : false,
                        'extra'       => $columnInfo->extra,
                        'comment'     => $columnInfo->column_comment,
                    ]);
                } else {
                    $table->columns()->create([
                        'name'        => $columnInfo->column_name,
                        'type'        => $columnInfo->column_type,
                        'default'     => $columnInfo->column_default,
                        'key'         => $columnInfo->column_key,
                        'is_nullable' => $columnInfo->is_nullable == 'YES' ? true : false,
                        'extra'       => $columnInfo->extra,
                        'comment'     => $columnInfo->column_comment,
                    ]);
                }
            }

            $columnNames = array_pluck($columnInfos, 'column_name');

            $table->columns()->whereNotIn('name', $columnNames)->delete();
            $bar->advance();
        }
        $bar->finish();
    }
}