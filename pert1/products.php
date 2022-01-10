<?php

class Catalogue
{

    function createProductColumn($columns, $listOfRawProduct)
    {
        foreach (array_keys($listOfRawProduct) as $listOfRawProductKey) {
            $listOfRawProduct[$columns[$listOfRawProductKey]] = $listOfRawProduct[$listOfRawProductKey];
            unset($listOfRawProduct[$listOfRawProductKey]);
        }
        return $listOfRawProduct;
    }

    function product($parameters)
    {
        $collectionOfListProduct = [];

        $raw_data = file($parameters['file_name']);
        foreach ($raw_data as $listOfRawProduct) {
            $collectionOfListProduct[] = $this->createProductColumn($parameters['columns'], explode(",", $listOfRawProduct));
        }

        //Membuat spasi collectionofListProduct
        // foreach ($collectionOfListProduct as $listOfRawProduct){
        //     print_r($listOfRawProduct);
        //     echo "<br>";
        // }

        return [
            'product' => $collectionOfListProduct,
            'gen_length' => count($collectionOfListProduct)
        ];
    }
}

class PopulationGenerator
{
    function createIndividu($parameters)
    {
        $catalogue = new Catalogue;
        $lengthOfGen = $catalogue->product($parameters)['gen_length'];

        for ($i = 0; $i <= $lengthOfGen; $i++) {
            $ret[] = rand(0, 1);
        }
        return $ret;
    }

    function createPopulation($parameters)
    {
        for ($i = 0; $i <= $parameters['population_size']; $i++) {
            $ret[] = $this->createIndividu($parameters);
        }
        foreach ($ret as $key => $val) {
            print_r($val);
            echo "<br>";
        }
        // print_r($ret); //Untuk parameter dari initial Population (Population Generator)
    }
}

$parameters = [
    'file_name' => 'products.txt',
    'columns' => ['item', 'price'],
    'population_size' => 10
];

//Catalogue Class
$katalog = new Catalogue;
$katalog->product($parameters); //Gunakan print_r()

//Population Generator
$initialPopulation = new PopulationGenerator;
$initialPopulation->createPopulation($parameters);
?>
const FILE_NAME = 'products.txt';
const COLUMNS = ['item', 'price'];
const POPULATION_SIZE = 5;
const BUDGET = 5000;
const STOPPING_VALUE = 10000;
const CROSSOVER_RATE = 0.8;