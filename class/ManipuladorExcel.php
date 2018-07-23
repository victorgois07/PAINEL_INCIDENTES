<?php


class ManipuladorExcel{
    protected $diretorio, $arquivos, $nameXML;
    private $dados;
    public $obj, $prioridade, $criado, $resolvido, $empresa, $grupo, $total, $incidente, $resolucao, $ic, $sumario, $nota;

    public function __construct($obj){
        $this->diretorio = "files/";
        $this->arquivos = glob($this->getDiretorio()."{*.xml}", GLOB_BRACE);
        $this->nameXML = array();
        $this->obj = $obj;
        $this->prioridade = $this->organizeARRAY($this->obj->parser->getColumn($this->localizarColuna("Prioridade*")));
        $this->criado = $this->organizeARRAY($this->obj->parser->getColumn($this->localizarColuna("Criado em")));
        $this->resolvido = $this->organizeARRAY($this->obj->parser->getColumn($this->localizarColuna("Data da Última Resolução")));
        $this->empresa = $this->organizeARRAY($this->obj->parser->getColumn($this->localizarColuna("Empresa de Suporte*")));
        $this->grupo = $this->organizeARRAY($this->obj->parser->getColumn($this->localizarColuna("Grupo Designado*+")));
        $this->incidente = $this->organizeARRAY($this->obj->parser->getColumn($this->localizarColuna("ID do Incidente*+")));
        $this->resolucao = $this->organizeARRAY($this->obj->parser->getColumn($this->localizarColuna("Resolução")));
        $this->ic = $this->organizeARRAY($this->obj->parser->getColumn($this->localizarColuna("IC+")));
        $this->sumario = $this->organizeARRAY($this->obj->parser->getColumn($this->localizarColuna("Sumário*")));
        $this->nota = $this->organizeARRAY($this->obj->parser->getColumn($this->localizarColuna("Notas")));
        $this->total = count($this->getCriado());
    }

    /*
     * Função para verificar quantos arquivos tem no diretorio;
     */
    private function verificarDiretorio(){
        $i=0;
        foreach ($this->arquivos as $nome){
            $arrayDados[$i] = $nome;
            $i++;
        }

        if (isset($arrayDados)) {
            $this->setNameXML($arrayDados);
        }
    }

    /*
     * Retorno o valor maior para extração do dados
     */

    private function dataMaiorLaco(){
        $this->verificarDiretorio();
        $arrayDatas = $this->stringDataValor();

        $k=1;
        for($i=0; $i<count($arrayDatas); $i++){
            if(strtotime($arrayDatas[$k]) > strtotime($arrayDatas[$i])){
                $posicao = $k;
            }
            elseif (strtotime($arrayDatas[$k]) < strtotime($arrayDatas[$i])){
                $k = $i;
                $posicao = $k;
            }
        }

        if (isset($posicao)) {
            return $posicao;
        }
    }

    private function stringDataValor(){
        $file = $this->getNameXML();

        if(isset($file)){
            for($i=0; $i<count($file); $i++){
                if (file_exists($file[$i])){
                    $fileExplode[$i] = explode("_",$file[$i]);
                    $valorData[$i] = $fileExplode[$i][1];
                }else{
                    return "ERRO ARQUIVOS NÃO EXISTEM";
                }
            }
        }

        if (isset($valorData)) {
            for ($i = 0; $i < count($valorData); $i++) {
                $exValorData[$i] = explode(".", $valorData[$i]);
                $finalValor[$i] = $exValorData[$i][0];
            }

            if (isset($finalValor)) {
                return $finalValor;
            }
        }
    }

    private function arquivosLocal(){
        $this->verificarDiretorio();
        $file = $this->getNameXML();
        return $file[$this->dataMaiorLaco()];
    }

    public function keyOrganizarOrdem($var){
        $var = array_unique($var);

        asort($var);

        foreach ($var as $v){
            $dados[] = $v;
        }

        if (isset($dados)) {
            return $dados;
        }
    }

    private function organizeARRAY($array){
        unset($array[0]);

        $i=0;
        foreach ($array as $ar){
            $ay[$i] = $ar;
            $i++;
        }

        if (isset($ay)) {
            return $ay;
        }
    }

    private function localizarColuna($col){
        $this->obj->parser->loadFile($this->arquivosLocal());
        $row = $this->obj->parser->getRow(1);
        for($i=0; $i<count($row); $i++){
            if($row[$i] == $col) {
                return $i+1;
            }
        }
    }



    public function teste(){
        return $this->arquivosLocal();
    }

    public function getDados(){
        return $this->dados;
    }

    public function setDados($dados){
        $this->dados = $dados;
    }

    public function getNameXML(){
        return $this->nameXML;
    }

    public function setNameXML($nameXML){
        $this->nameXML = $nameXML;
    }

    public function getDiretorio(){
        return $this->diretorio;
    }

    public function setDiretorio($diretorio){
        $this->diretorio = $diretorio;
    }

    public function getArquivos(){
        return $this->arquivos;
    }

    public function setArquivos($arquivos){
        $this->arquivos = $arquivos;
    }

    public function getObj(){
        return $this->obj;
    }

    public function setObj($obj){
        $this->obj = $obj;
    }

    /**
     * @return mixed
     */
    public function getPrioridade()
    {
        return $this->prioridade;
    }

    /**
     * @param mixed $prioridade
     */
    public function setPrioridade($prioridade)
    {
        $this->prioridade = $prioridade;
    }

    /**
     * @return mixed
     */
    public function getCriado()
    {
        return $this->criado;
    }

    /**
     * @param mixed $criado
     */
    public function setCriado($criado)
    {
        $this->criado = $criado;
    }

    /**
     * @return mixed
     */
    public function getResolvido()
    {
        return $this->resolvido;
    }

    /**
     * @param mixed $resolvido
     */
    public function setResolvido($resolvido)
    {
        $this->resolvido = $resolvido;
    }

    /**
     * @return mixed
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * @param mixed $empresa
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }

    /**
     * @return mixed
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * @param mixed $grupo
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }
}

?>