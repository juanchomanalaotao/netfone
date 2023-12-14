<?php
namespace App\GraphQL\Mutations;

use App\Models\Contacts;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ContactMutations
{
    public function __invoke($rootValue,array $args,GraphQLContext $graphQLContext){
        // $data = Contacts::create([
        //     'name'=>$args['name'],
        //     'contact_no'=>$args['contact_no']
        // ]);
            \Log::info(json_encode($args));
        // return $data;
    }
}
