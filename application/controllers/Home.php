<?php


class Home extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->database();	

	}
	public function index()
	{
        // load view admin/home.php
		// $data['title']='Profil Halal Center';	
		$this->load->view("home");		
	}

	public function profil()
	{        				
		$this->load->view("profil");		
	}

	public function hcuser(){
		$data['title'] ='Profile';
		$this->load->view("partial/hchead.php", $data);    		
		$this->load->view("hcprofil");
		$this->load->view("partial/hcfooter.php"); 
	}

	public function product(){
	 
		$data['title'] ='Pengajuan Penelitian Produk';		  		
		$this->load->view("user/product");
		
	}


	public function save_product(){
		$data = array(
			'namapj'=>$this->input->post('namapj'),
			'nama_produk'=>$this->input->post('namaproduk'),
			'ijin_usaha'=>$this->input->post('ijinusaha'),
			'nama_usaha'=>$this->input->post('namausaha'),
			'jenis_produk'=>$this->input->post('jenis'),
			'alamat'=>$this->input->post('alamat'),
			'deskripsi'=>$this->input->post('deskripsi')
		);
		$this->db->insert("produk",$data);
		$this->db->affected_rows();		
		redirect("home/homeuser");
       
	}
 
	/*public function product()
	{
		
		$this->form_validation->set_rules('name','Name','required|trim');		
		$this->form_validation->set_rules('price','Price','required|trim');		
		$this->form_validation->set_rules('description','Description','required|trim');		
		
	
		if ( $this->form_validation->run() == false) {
	
			$data['title']='Pengajuan Produk';
			$this->load->view("partial/hchead.php", $data);
			$this->load->view("partial/hcheader.php");     		
			$this->load->view("product.php");		
			$this->load->view("partial/hcfooter.php"); 
		} else {
			$data = [
				'name'=> htmlspecialchars($this->input->post('name', true)),
				'price'=> htmlspecialchars($this->input->post('price', true)),
				'image'=> 'default.jpg',
				'description'=> htmlspecialchars($this->input->post('description', true))											
			];

			$this->db->insert('product',$data);
			$this->session->set_flashdata('message','<div class="alert
			alert-success" role="alert">Congratulation! your 
			product has been registered!</div>');			
			redirect('user');
		}
	} */

	public function hclogin()
	{   
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		$this->form_validation->set_rules('password','Password','trim|required');
		if($this->form_validation->run() == false){		
			$data['title']='User Log In';
			$this->load->view("partial/hchead.php", $data); 
			$this->load->view("partial/hcheader.php");     		
			$this->load->view("hclogin");		
			$this->load->view("partial/hcfooter.php"); 
		} else {
			//validasi sukses
			$this->_login();
		}
	}
	
	function logout(){				
		$this->session->sess_destroy();
			redirect('home');	
			
	}

	private function _login(){
		$email = $this->input->post('email');
		$password = md5($this->input->post('password'));
		$query = $this->db->query("SELECT * FROM user WHERE email = '$email' AND password = '$password'");
		$hasil = $query->result();
		if(!empty($hasil)){
			foreach ($hasil as $data) {
				$nama = $data->email;
				$role = $data->role_id;
				$aktif = $data->is_active;	
			}
			$session_user = array(
				'nama'=> $nama
			);
				if($role==1 && $aktif==1){
					redirect("home/homeadmin");	
					}else if($role==2 && $aktif==1){
					redirect("home/homeuser");
					}else{
						redirect("home");
					}

			print_r($role);
		}
		

		// $user = $this->db->get_where('user', ['email'=> $email])->row_array();
		// //jika user ada
		// if($user){
		// 	//user aktif
		// 	if($user['is_active'] == 1){
		// 			// cekk password
		// 			if (password_verify($password, $user['password'])) {
		// 				$data = [
		// 					'email'=> $user['email'],
		// 					'role_id'=>$user['role_id']
		// 				];
		// 				$this->session->set_userdata($data);
		// 				if($user['role_id'] == 1){
		// 					redirect('admin');
		// 				}else{
		// 					redirect('user');
		// 				}

		// 			}else{
		// 				$this->session->set_flashdata('message','<div class="alert
		// 				alert-success" role="alert"> Wrong Password!</div>');			
		// 				redirect('home/hclogin');
		// 			}
			
		// 	} else {
		// 		$this->session->set_flashdata('message','<div class="alert
		// 		alert-success" role="alert">Email has not been activated!</div>');			
		// 		redirect('home/hclogin');
		// 		}
		// }else{
		// 		$this->session->set_flashdata('message','<div class="alert
		// 		alert-success" role="alert">Email is not registered!</div>');			
		// 		redirect('home/hclogin');
		// }
	}
	
	public function hcregistrasi()
	{   
		$this->form_validation->set_rules('name','Name','required|trim');		
		$this->form_validation->set_rules('email','Email','required|trim|valid_email|is_unique[user.email]'
		,['is_unique'=>'']);
		$this->form_validation->set_rules('instansi','Instansi','required|trim');		
		$this->form_validation->set_rules('no_hp','No_hp','required|trim');		
		$this->form_validation->set_rules('password1','Password','required|trim|min_length[3]|matches[password2]',['matches'=>'Password dont match!','min_length'=>'Password too Short!']);
		$this->form_validation->set_rules('password2','Password','required|trim|matches[password1]');

		if ( $this->form_validation->run() == false) {
	
			$data['title']='Registrasi User';
			$this->load->view("partial/hchead.php", $data);
			$this->load->view("partial/hcheader.php");     		
			$this->load->view("hcregistrasi");		
			$this->load->view("partial/hcfooter.php"); 

		} else {
			$data = [
				'name'=> htmlspecialchars($this->input->post('name', true)),
				'email'=> htmlspecialchars($this->input->post('email', true)),
				'instansi'=> htmlspecialchars($this->input->post('instansi', true)),
				'no_hp'=> htmlspecialchars($this->input->post('no_hp', true)),				
				'password' => md5($this->input->post('password1')),
				'role_id' => 2,
				'is_active' => 1,
				'date_created'=> time()
								
			];

			$this->db->insert('user',$data);
			$this->session->set_flashdata('message','<div class="alert
			alert-success" role="alert">Congratulation! your 
			account has been created. Please Login</div>');			
			redirect('home/hclogin');
		}
	}

	public function riset()
	{	
		
		$this->load->view("riset");
	}
	
	public function pengujian()
	{
		
		$this->load->view("pengujian");
	}
	
	public function galeri()
	{
		
		$this->load->view("galeri");
	}
	
	public function ia()
	{		
		$this->load->view("ia");
	}
	
	public function layanan()
	{
		
		$this->load->view("layanan");
	}
	
	public function scope()
	{
	
		$this->load->view("scope");
	}		
	
	public function kontak()
	{	
		
		$this->load->view("kontak");
	}


	//USER
	public function homeuser()
	{
		
		$this->load->view("homeuser");
	}

	public function u_profil()
	{	    				
		$this->load->view("user/u_profil");
	}
	public function u_riset()
	{	    				
		$this->load->view("user/u_riset");
	}
	
	public function u_layanan()
	{	    				
		$this->load->view("user/u_layanan");
	}
	
	public function u_scope()
	{	    				
		$this->load->view("user/u_scope");
	}
	public function u_galeri()
	{	    				
		$this->load->view("user/u_galeri");
	}
	public function u_kontak()
	{	    				
		$this->load->view("user/u_kontak");
	}

	//ADMIN
	public function homeadmin()
	{	    				
		$this->load->view("homeadmin");
	}
	public function tatauser()
	{	    				
		$query= $this->db->query("SELECT * FROM user ");
		$data['tampil'] = $query->result();		
		$this->load->view("admin/tatauser", $data);
	}	
	public function edituser($id)
	{		
		$query= $this->db->query("SELECT * FROM user WHERE id = '$id'");
		$data['hasil'] = $query->result();
			$this->load->view('admin/edituser',$data);
	}
	public function edit_user($id){
		// $id = $this->input->post('id');
		$nama = $this->input->post('nama');
		$email = $this->input->post('email');
		$instansi = $this->input->post('instansi');
		$nohp = $this->input->post('nohp');
		$password = $this->input->post('password');
		$roleid = $this->input->post('roleid');
		$aktif = $this->input->post('aktif');
		
		$data=array(
					'id'=> $id,
					'name'=> $nama,
					'email'=> $email,
					'instansi'=> $instansi,
					'no_hp'=> $nohp,
					'password'=> $password,
					'role_id'=> $roleid,
					'is_active'=> $aktif,		
					'date_created'=> $tanggal,		
				);
		// $this->db->set($data);
		$this->db->where('id', $id);
		$this->db->update('user',$data);		
		redirect('home/tatauser');
	}

	

	public function dataproduk()
	{
		$query= $this->db->query("SELECT * FROM produk ");
		$data['tampil'] = $query->result();
		// $data1['tampil'] = $this->db->get('produk');
		// print_r();
		$this->load->view("admin/dataproduk",$data);
	}
	public function editproduct($id)
	{		
		$query= $this->db->query("SELECT * FROM produk WHERE id_produk = '$id'");
		$data['tampol'] = $query->result();
			$this->load->view('admin/editproduct',$data);
	}
	public function edit($id){
		// $id = $this->input->post('id');
		$namapj = $this->input->post('namapj');
		$namaproduk = $this->input->post('namaproduk');
		$ijinusaha = $this->input->post('ijinusaha');
		$namausaha = $this->input->post('namausaha');
		$jenis = $this->input->post('jenis');
		$alamat = $this->input->post('alamat');
		$deskripsi = $this->input->post('deskripsi');

		$data=array(
					'id_produk'=> $id,
					'namapj'=> $namapj,
					'nama_produk'=> $namaproduk,
					'ijin_usaha'=> $ijinusaha,
					'nama_usaha'=> $namausaha,
					'jenis_produk'=> $jenis,
					'alamat'=> $alamat,
					'deskripsi'=> $deskripsi,		
				);
		// $this->db->set($data);
		$this->db->where('id_produk', $id);
		$this->db->update('produk',$data);		
		redirect('home/dataproduk');
	}
	// public function edit_data($where, $table)
	// {
	// 	return $this->db->get_where($table, $where);
	// }
	// public function prosesedit()
	// {
		// $id = $this->input->post('id');
		// $namapj = $this->input->post('namapj');
		// $namaproduk = $this->input->post('namaproduk');
		// $ijinusaha = $this->input->post('ijinusaha');
		// $namausaha = $this->input->post('namausaha');
		// $jenis = $this->input->post('jenis');
		// $alamat = $this->input->post('alamat');
		// $deskripsi = $this->input->post('deskripsi');

	// 	$data=array(
	// 		'id_produk'=> $id,
	// 		'namapj'=> $namapj,
	// 		'namaproduk'=> $namaproduk,
	// 		'ijin_usaha'=> $ijinusaha,
	// 		'nama_usaha'=> $namausaha,
	// 		'jenis_produk'=> $jenis,
	// 		'alamat'=> $alamat,
	// 		'deskripsi'=> $deskripsi,

	// 	);

	// 	$where=array('id_produk'=>$id);

	// 	$this->m_data->update($where, $data,'produk'); 

	// 	redirect('home/dataproduk');
	// }

	// function update ($where, $data, $table)
	// {
	// 	$this->db->where($where);
	// 	$this->db->update($table, $data);
	// }

	function hapus($id){
		$this->db->where('id_produk', $id);
		$this->db->delete('produk');		
		redirect('home/dataproduk');
	}
	function hapususer($id){
		$this->db->where('id', $id);
		$this->db->delete('user');		
		redirect('home/tatauser');
	}

	// function hapusdata($where, $table)
	// {
	// 	$this->db->where($where);
	// 	$this->db->update($table);
	// }

	// public function editproduct($id)
	// {		
	// 	$this->db->where($id);
	// 	$this->db->update('produk');
	// }

}