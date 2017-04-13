<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // $this->call(UsersTableSeeder::class);
        //echo 'h';
            factory(App\Review::class, 500)->create();
            factory(App\Event::class, 20)->create();

        		echo 'h1';
        	factory(App\Cafe::class, 100)->create()->each(function($y){
        		           		$ran=App\Review::all()->random(5);
           	           		foreach ($ran as $key => $value) {
           	           			$b[]=$value;           	           		
           	           		}          	       		
   				$y->reviews()->saveMany($b);

        	});
        		echo 'h2';
        	factory(App\User::class, 20)->create()->each(function($x){
           		echo 'h';
           		$a=array();
           		//print_r($a);
           		$b=array();
           		$ran=App\Cafe::all()->random(5);
           		//var_dump($ran);
           	           		foreach ($ran as $key => $value) {
           	           			$a[]=$value->id;
           	           		}
           	           		//var_dump($a);
   				$x->favorite_cafes()->attach($a);
           		
           		$ran=App\Review::all()->random(5);
           	           		foreach ($ran as $key => $value) {
           	           			$b[]=$value;           	           		
           	           		}
           	           		
           	       		
   				$x->reviews()->saveMany($b);
   				


   			
   			});
  			   				//echo "j";
          App\Event::all()->each(function($k){
            $k->cafe()->associate(App\Cafe::all()->random(1)->first()->id);
            $k->save();
          });
   			App\Review::all()->each(function($c){
   				//var_dump($c->cafe_id);

   				if($c->cafe_id==-1)
   				{
   					//echo "cid";
   					//var_dump($c->cafe_id);
   					$c->cafe()->associate(App\Cafe::all()->random(1)->first()->id);
   					//echo" new cid:";
   					//var_dump($c->cafe_id);
   					$c->save();
   				}
   				if($c->user_id==-1)
   				{
   					$c->user()->associate(App\User::all()->random(1)->first()->id);
   					$c->save();
   				}

   			});




           	/*	$ran=App\Cafe::all();
           		foreach ($ran as $key => $value) {
           			var_dump(get_object_vars($value));
           			echo '----';  

           		}*/
           		
           	//var_dump(factory(App\Cafe::class));
           	// $x->favorite_cafes()->save(factory(App\Cafe::class)->random(3)->create()->make());
           			

           	//var_dump($x);
         //  });
        /*   	App\User::all()
           	           		$ran=App\Cafe::all()->random(5);
           	           		foreach ($ran as $key => $value) {
           	           			$a[]=($value->id);
           	           		}
           		var_dump($a);
           		echo '----';
           		*/
          
//$fzu=App\User::first();
//		$fzu->fasz= "fasz";
//           		var_dump($fzu);
    }
}

   