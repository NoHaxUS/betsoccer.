<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'criar-role',
            'display_name' => 'Criar Role',
            'description' => 'Permissao para criar novas Roles'
        ]);
        Permission::create([
            'name' => 'editar-role',
            'display_name' => 'Editar Role',
            'description' => 'Permissao para editar Roles'
        ]);
        Permission::create([
            'name' => 'consultar-role',
            'display_name' => 'Consultar Role',
            'description' => 'Permissao para consultar Roles'
        ]);
        Permission::create([
            'name' => 'excluir-role',
            'display_name' => 'Excluir Role',
            'description' => 'Permissao para excluir Roles'
        ]);
        Permission::create([
            'name' => 'relacionar-role',
            'display_name' => 'Relacionar Role',
            'description' => 'Permissao para criar relação entre role a users (associar e desassociar)'
        ]);
        Permission::create([
            'name' => 'criar-permission',
            'display_name' => 'Criar Permission',
            'description' => 'Permissao para criar novas Permissions'
        ]);
        Permission::create([
            'name' => 'editar-permission',
            'display_name' => 'Editar Permission',
            'description' => 'Permissao para editar Permissions'
        ]);
        Permission::create([
            'name' => 'consultar-permission',
            'display_name' => 'Consultar Permission',
            'description' => 'Permissao para consultar Permissions'
        ]);
        Permission::create([
            'name' => 'excluir-permission',
            'display_name' => 'Excluir Permission',
            'description' => 'Permissao para excluir Permissions'
        ]);
        Permission::create([
            'name' => 'relacionar-permission',
            'display_name' => 'Relacionar Permission',
            'description' => 'Permissao para relacionar permission a role (associar e desassociar)'
        ]);
        Permission::create([
            'name' => 'criar-user',
            'display_name' => 'Criar User',
            'description' => 'Permissao para criar novos users'
        ]);
        Permission::create([
            'name' => 'editar-user',
            'display_name' => 'Editar User',
            'description' => 'Permissao para editar users'
        ]);
        Permission::create([
            'name' => 'relacionar-users',
            'display_name' => 'Relacionar Users',
            'description' => 'Permissao para criar relacao entre users'
        ]);
        Permission::create([
            'name' => 'consultar-user',
            'display_name' => 'Consultar User',
            'description' => 'Permissao para consultar users'
        ]);
        Permission::create([
            'name' => 'excluir-user',
            'display_name' => 'Excluir User',
            'description' => 'Permissao para excluir users'
        ]);
        Permission::create([
            'name' => 'ativar-user',
            'display_name' => 'Ativar User',
            'description' => 'Permissao para ativar users'
        ]);
        Permission::create([
            'name' => 'desativar-user',
            'display_name' => 'Desativar User',
            'description' => 'Permissao para desativar users'
        ]);
        Permission::create([
            'name' => 'criar-campeonato',
            'display_name' => 'Criar Campeonato',
            'description' => 'Permissao para cadastrar dados de novos campeonatos'
        ]);
        Permission::create([
            'name' => 'editar-campeonato',
            'display_name' => 'Editar Campeonato',
            'description' => 'Permissao para editar dados de campeonatos'
        ]);
        Permission::create([
            'name' => 'consultar-campeonato',
            'display_name' => 'Consultar Campeonato',
            'description' => 'Permissao para consultar campeonatos'
        ]);
        Permission::create([
            'name' => 'excluir-campeonato',
            'display_name' => 'Excluir Campeonato',
            'description' => 'Permissao para excluir campeonatos'
        ]);
        Permission::create([
            'name' => 'criar-time',
            'display_name' => 'Criar Time',
            'description' => 'Permissao para cadastrar dados de novos times'
        ]);
        Permission::create([
            'name' => 'editar-time',
            'display_name' => 'Editar Time',
            'description' => 'Permissao para editar dados de times'
        ]);
        Permission::create([
            'name' => 'consultar-time',
            'display_name' => 'Consultar Time',
            'description' => 'Permissao para consultar times'
        ]);
        Permission::create([
            'name' => 'excluir-time',
            'display_name' => 'Excluir Time',
            'description' => 'Permissao para excluir times'
        ]);
        Permission::create([
            'name' => 'criar-jogo',
            'display_name' => 'Criar Jogo',
            'description' => 'Permissao para cadastrar dados de novos jogos'
        ]);
        Permission::create([
            'name' => 'editar-jogo',
            'display_name' => 'Editar Jogo',
            'description' => 'Permissao para editar dados de jogos'
        ]);
        Permission::create([
            'name' => 'consultar-jogo',
            'display_name' => 'Consultar Jogo',
            'description' => 'Permissao para consultar jogos'
        ]);
        Permission::create([
            'name' => 'excluir-jogo',
            'display_name' => 'Excluir Jogo',
            'description' => 'Permissao para excluir jogos'
        ]);
        Permission::create([
            'name' => 'ativar-jogo',
            'display_name' => 'Ativar Jogo',
            'description' => 'Permissao para ativar jogos'
        ]);
        Permission::create([
            'name' => 'desativar-jogo',
            'display_name' => 'Desativar Jogo',
            'description' => 'Permissao para desativar jogos'
        ]);
        Permission::create([
            'name' => 'editar-placar',
            'display_name' => 'Editar Placar',
            'description' => 'Permissao para editar placar de jogos'
        ]);
        Permission::create([
            'name' => 'consultar-palpite',
            'display_name' => 'Consultar Palpite',
            'description' => 'Permissao para consultar palpite de jogos'
        ]);
        Permission::create([
            'name' => 'criar-aposta',
            'display_name' => 'Criar Aposta',
            'description' => 'Permissao para criar novas apostas'
        ]);
        Permission::create([
            'name' => 'editar-aposta',
            'display_name' => 'Editar Aposta',
            'description' => 'Permissao para editar apostas'
        ]);
        Permission::create([
            'name' => 'consultar-aposta',
            'display_name' => 'Consultar Aposta',
            'description' => 'Permissao para consultar apostas'
        ]);
        Permission::create([
            'name' => 'excluir-aposta',
            'display_name' => 'Excluir Aposta',
            'description' => 'Permissão para excluir apostas'
        ]);
        Permission::create([
            'name' => 'validar-aposta',
            'display_name' => 'Validar Aposta',
            'description' => 'Permissao para validar apostas'
        ]);
        Permission::create([
            'name' => 'acertar-aposta',
            'display_name' => 'Acertar Aposta',
            'description' => 'Permissao para realizar acerto de apostas'
        ]);
        Permission::create([
            'name' => 'criar-telefone',
            'display_name' => 'Criar Telefone',
            'description' => 'Permissão para cadastrar dados de novos telefones'
        ]);
        Permission::create([
            'name' => 'editar-telefone',
            'display_name' => 'Editar Telefone',
            'description' => 'Permissao para editar dados de telefones'
        ]);
        Permission::create([
            'name' => 'consultar-telefone',
            'display_name' => 'Consultar Telefone',
            'description' => 'Permissão para consultar telefones'
        ]);
        Permission::create([
            'name' => 'excluir-telefone',
            'display_name' => 'Excluir Telefone',
            'description' => 'Permissao para excluir telefones'
        ]);
        Permission::create([
            'name' => 'criar-dispositivo',
            'display_name' => 'Criar Dispositivo',
            'description' => 'Permissao para cadastrar dados de novos dispositivos'
        ]);
        Permission::create([
            'name' => 'editar-dispositivo',
            'display_name' => 'Editar Dispositivo',
            'description' => 'Permissao para editar dados de dispositivos'
        ]);
        Permission::create([
            'name' => 'consultar-dispositivo',
            'display_name' => 'Consultar Dispositivo',
            'description' => 'Permissao para consultar dispositivos'
        ]);
        Permission::create([
            'name' => 'excluir-dispositivo',
            'display_name' => 'Excluir Dispositivo',
            'description' => 'Permissao para excluir dispositivos'
        ]);
    }
}