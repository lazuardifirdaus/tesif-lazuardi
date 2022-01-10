<?php

class Parameters{
    const FILE_NAME = 'products.txt';
    const COLUMNS = ['item', 'price'];
    const POPULATION_SIZE = 10;
    const BUDGET = 280000;
    const STOPPING_VALUE = 10000;
}

class Catalogue{

    function createProductColumn($listOfRawProduct){
        foreach (array_keys($listOfRawProduct) as $listOfRawProductKey){
            $listOfRawProduct[Parameters::COLUMNS[$listOfRawProductKey]] = $listOfRawProduct[$listOfRawProductKey];
            unset($listOfRawProduct[$listOfRawProductKey]);
        }
        return $listOfRawProduct;
    }

    function product(){
        $collectionOfListProduct = [];

        $raw_data = file(Parameters::FILE_NAME);
        foreach ($raw_data as $listOfRawProduct) {
            $collectionOfListProduct[] = $this->createProductColumn(explode(",", $listOfRawProduct));
        }

        return $collectionOfListProduct;
        //Membuat spasi collectionofListProduct
        // foreach ($collectionOfListProduct as $listOfRawProduct){
        //     print_r($listOfRawProduct);
        //     echo "<br>";
        // }

        // return [
        //     'product'=> $collectionOfListProduct,
        //     'gen_length' => count($collectionOfListProduct)
        // ];
    }
}

class Individu{

    function countNumberOfGen(){
        $catalogue = new Catalogue;
        return count($catalogue->product());
    }

    function createRandomIndividu(){
        
        for ($i=0; $i <= $this->countNumberOfGen()-1; $i++) { 
            # code...
            $ret[] = rand(0, 1);
        }
        return $ret;
        // echo $this->countNumberOfGen();exit();
        // for ($i=0; $i <= $ ; $i++) { 
        //     # code...
        // }
    }
}

class Population{
    // function createIndividu(){
    //     $catalogue = new Catalogue;
    //     $lengthOfGen = $catalogue->product($parameters)['gen_length'];

    //     for ($i=0; $i <= $lengthOfGen; $i++) { 
    //         $ret[] = rand(0,1);
    //     }
    //     return $ret;
    // }

    function createRandomPopulation(){
        $individu = new Individu;
        for($i = 0; $i <= Parameters::POPULATION_SIZE; $i++){
            $ret[] = $individu->createRandomIndividu();
        }
    
        return $ret;
        // foreach($ret as $key => $val){
        //     print_r($val);
        //     echo "<br>";
        // }
        // print_r($ret); //Untuk parameter dari initial Population (Population Generator)
    }
}

class Fitness{
    
    function selectingItem($individu){
        $catalogue = new Catalogue;
        foreach ($individu as $individuKey => $binaryGen) {
            # code...
            if ($binaryGen === 1) {
                # code...
                $ret[] = [
                    'selectedKey' => $individuKey,
                    'selectedPrice' => $catalogue->product()[$individuKey]['price'],
                ];
            }
        }
        return $ret;
    }

    function calculateFitnessValue($individu){
        // print_r($this->selectingItem($individu));
        // $this->selectingItem($individu);
        // exit();

        return array_sum(array_column($this->selectingItem($individu),'selectedPrice'));
    }

    function countSelectedItem($individu){
        return count($this->selectingItem($individu));
    }

    function searchBestIndividu($fits, $maxItem, $numberOfIndividuHasMaxItems)
    {
        if ($numberOfIndividuHasMaxItems === 1) {
            # code...
            $index = array_search($maxItem, array_column($fits, 'numberOfSelectedItem'));
            // echo '<br>';
            // print_r($fits[$index]);
            // echo '<br>';
            return $fits[$index];
        }
        else {
            # code...
            foreach ($fits as $key => $val) {
                # code...
                if ($val['numberOfSelectedItem'] === $maxItem) {
                    # code...
                    echo 'Array ke: '.$key.' | '.'Nilai Fitness = '.$val['fitnessValue'].'<br>';
                    $ret[] = [
                        'individuKey' => $key,
                        'fitnessValue' => $val['fitnessValue'],
                    ];
                }
            }

            if (count(array_unique(array_column($ret, 'fitnessValue'))) === 1) {
                # code...
                $index = rand(0, count($ret) - 1);
            } else {
                # code...
                $max = max(array_column($ret, 'fitnessValue'));
                $index = array_search($max, array_column($ret, 'fitnessValue'));
            }
            echo "Hasil: ";
            return $ret[$index];
            // print_r($ret[$index]);

        }
    }

    function isFound($fits)
    {

        $countedMaxItems = array_count_values(array_column($fits, 'numberOfSelectedItem'));
        echo '<br>';
        print_r($countedMaxItems);
        echo '<br>';
        $maxItem = max(array_keys($countedMaxItems));
        echo 'Max Item: '.$maxItem;
        echo '<br>';
        echo 'Jumlah Max Item: '.$countedMaxItems[$maxItem];
        // if ($fitnessValue <= Parameters::BUDGET) {
        //     # code...
        //     return TRUE;
        // }
        $numberOfIndividuHasMaxItems = $countedMaxItems[$maxItem];
        echo '<br>';

        //Fungsi searchBestIndividu Fitness (Mencari nilai item individu yang memiliki nilai maksimal )
        $bestFitnessValue = $this->searchBestIndividu($fits, $maxItem, $numberOfIndividuHasMaxItems)['fitnessValue'];

        // print_r($bestFitnessValue);
        echo '<br>Best fitness value: '.$bestFitnessValue;

        $residual = Parameters::BUDGET - $bestFitnessValue;
        echo '<br>';
        echo ' Residual: '.$residual;

        if ($residual <= Parameters::STOPPING_VALUE && $residual > 0) {
            # code...
            return TRUE;
        }
    }


    function isFit($fitnessValue){
        if ($fitnessValue <= Parameters::BUDGET) {
            # code...
            return TRUE;
        }
    }

    function fitnessEvaluation($population)
    {
        $catalogue = new Catalogue;
        // print_r($population);
        foreach ($population as $listOfIndividuKey => $listOfIndividu) {
            # code...
            echo 'Individu-'. $listOfIndividuKey.'<br>';
            foreach ($listOfIndividu as $individuKey => $binaryGen) {
                # code...
                echo $binaryGen."&nbsp;&nbsp";
                print_r($catalogue->product()[$individuKey]);
                echo "<br>";
            }
            $fitnessValue = $this->calculateFitnessValue($listOfIndividu);
            $numberOfSelectedItem = $this->countSelectedItem($listOfIndividu);
            echo 'Max. Item: '.$numberOfSelectedItem;
            echo ' Fitness value: '.$fitnessValue;
            
            if ($this->isFit($fitnessValue)) {
                # code...
                echo ' (Fit) '.'<br>';
                $fits[] = [
                    'selectedIndividuKey' => $listOfIndividuKey,
                    'numberOfSelectedItem' => $numberOfSelectedItem,
                    'fitnessValue' => $fitnessValue,
                ];
                print_r($fits);
            } else {
                echo ' (Not Fit)';
            }
            
            echo '<p>';
            print_r('<br>');
        }
        //Fungsi isFound Fitness
        if ($this->isFound($fits)) {
            # code...
            echo '<br>';
            echo ' Found';
            echo '<br>';
        } else {
            # code...
            echo '<br>';
            echo ' >> Next Generation';
            echo '<br>';
        }

        // $this->isFound($fits);
        
    }
}
// //Catalogue Class
// $katalog = new Catalogue;
// $katalog->product($parameters); //Gunakan print_r()

// //Population Generator
$initialPopulation = new Population;
$initialPopulation = $initialPopulation->createRandomPopulation();

$fitness = new Fitness;
$fitness->fitnessEvaluation($initialPopulation);
echo '<br>';
// print_r($fitness);
// $individu = new Individu;
// print_r($individu->createRandomIndividu());