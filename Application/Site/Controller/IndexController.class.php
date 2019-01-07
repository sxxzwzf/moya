<?php
// +----------------------------------------------------------------------
// | ħ� [ �� ��Ч ׿Խ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ffgame.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <7661660@qq.com>
// +----------------------------------------------------------------------
// | ��Ȩ������ħ櫲���һ�������������ħ櫹ٷ��Ƴ�����ҵԴ�룬�Ͻ���δ����ɵ������
// | ���������ơ�������ʹ��ħ櫵�������룬����Υ����������ɾ���������������ٳе���Ӧ
// | �������εķ��ա������Ҫȡ�ùٷ���Ȩ������ϵ�ٷ�http://www.ffgame.com
// +----------------------------------------------------------------------
namespace Site\Controller;

use Home\Controller\HomeController;

/**
 * վ�������
 * �ÿ�������Ҫ���TP����������󶨴Ӷ�֧��վȺģ��ÿ����վ���Լ�������
 * @author jry <7661660@qq.com>
 */
class IndexController extends HomeController
{
    // ��վ������Ϣ
    protected $info = array();

    /**
     * ��ʼ������
     * @author jry <7661660@qq.com>
     */
    protected function _initialize()
    {
        // �����ʼ������
        parent::_initialize();

        // ����ģ���׺
        C('TMPL_TEMPLATE_SUFFIX', '.htm');

        $info             = C('site_config');
        $info['logo_url'] = get_cover($info['logo']);
        $info['homepage'] = U('Site/Site/index');
        $this->info       = $info;

        // ���ӷ��ʴ���
        $con       = array();
        $con['id'] = C('Site_config.theme');
        D('Theme')->where($con)->setInc('view_count');
    }

    /**
     * ��ҳ
     * @author jry <7661660@qq.com>
     */
    public function index()
    {
        // ��վ����
        $info = $this->info;

        // ��ȡ�õ�Ƭ�б�
        $slider_model = D('Site/Slider');
        $con          = array();
        $slider_list  = $slider_model->where($con)->limit(10)->order('sort desc, id desc')->select();
        $this->assign('slider_list', $slider_list);

        // ��ȡģ�嶩����Ϣ
        $theme_model = D('Site/Theme');
        $con         = array();
        $con['id']   = C('Site_config.theme');
        $theme_info  = $theme_model->where($con)->find();
        if (!$theme_info) {
            $this->error('�����ں�̨������վģ��');
        }

        // ģ���Զ�������
        $theme_info['config'] = json_decode($theme_info['config'], true);
        $this->assign('config_info', $theme_info['config']);

        $this->assign('meta_title', "��ҳ");

        // ��ʾҳ��
        if (C('CURRENT_THEME')) {
            // ����Ŀ¼
            $info['theme_path'] = __ROOT__ . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'];
            $this->assign('theme_path_header', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/footer.htm');
            $this->assign('info', $info);
            $this->display('Site/theme/' . $theme_info['name'] . '/index');
        } else {
            // ����Ŀ¼
            $info['theme_path'] = __ROOT__ . '/Application/Site/View/Site/theme/' . $theme_info['name'];
            $this->assign('theme_path_header', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/footer.htm');
            $this->assign('info', $info);
            $this->display('Site/theme/' . $theme_info['name'] . '/index');
        }
    }

    /**
     * �����б�
     * @author jry <7661660@qq.com>
     */
    public function lists($cid)
    {
        // ��վ����
        $info = $this->info;

        // ��ȡ������Ϣ
        $category_model = D('Category');
        $con            = array();
        $con['status']  = '1';
        $con['id']      = $cid;
        $category_info  = $category_model->where($con)->find();
        if (!$category_info) {
            $this->error('���಻����');
        }

        // ��ȡ�뵱ǰ����ͬ���ķ���
        if (!$category_info['pid']) {
            $con           = array();
            $con['status'] = '1';
            $con['pid']    = $category_info['pid'];
            $category_list = $category_model->getCategoryTree($category_info['id']);
        } else {
            $con           = array();
            $con['status'] = '1';
            $con['pid']    = $category_info['pid'];
            $category_list = $category_model->getSameLevelCategoryTree($category_info['id']);
        }

        // ��ȡ���м��������
        $breadcrumb_list = $category_model->getParentCategory($category_info['id']);

        // ��ȡģ�嶩����Ϣ
        $theme_model = D('Site/Theme');
        $con         = array();
        $con['id']   = C('Site_config.theme');
        $theme_info  = $theme_model->where($con)->find();
        if (!$theme_info) {
            $this->error('�����ں�̨������վģ��');
        }

        // ģ���Զ�������
        $theme_info['config'] = json_decode($theme_info['config'], true);
        $this->assign('config_info', $theme_info['config']);

        // ��ȡ�����б�
        $article_model = D('Article');
        $con           = array();
        $con['status'] = '1';
        $con['cid']    = $cid;
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $data_list     = $article_model->where($con)->page($p, 9)->select();

        // ��ҳ
        $page = new \lyf\Page(
            $article_model->where($con)->count(),
            9
        );

        $this->assign('category_info', $category_info);
        $this->assign('category_list', $category_list);
        $this->assign('breadcrumb_list', $breadcrumb_list);
        $this->assign('data_list', $data_list);
        $this->assign('page', $page->show());
        $this->assign('meta_title', $category_info['title'] . "-" . $info['title']);

        // ��ʾҳ��
        if (C('CURRENT_THEME')) {
            // ����Ŀ¼
            $info['theme_path'] = __ROOT__ . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'];
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/footer.htm');
            $this->display('Site/theme/' . $theme_info['name'] . '/' . ($category_info['lists_template'] ?: 'lists_list'));
        } else {
            // ����Ŀ¼
            $info['theme_path'] = __ROOT__ . '/Application/Site/View/Site/theme/' . $theme_info['name'];
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/footer.htm');
            $this->display('Site/theme/' . $theme_info['name'] . '/' . ($category_info['lists_template'] ?: 'lists_list'));
        }
    }

    /**
     * ��ҳ
     * @author jry <7661660@qq.com>
     */
    public function page($cid)
    {
        // ��վ����
        $info = $this->info;

        // ��ȡ������Ϣ
        $category_model = D('Category');
        $con            = array();
        $con['status']  = '1';
        $con['id']      = $cid;
        $category_info  = $category_model->where($con)->find();
        if (!$category_info) {
            $this->error('���಻����');
        }

        // ��ȡ�뵱ǰ����ͬ���ķ���
        if (!$category_info['pid']) {
            $con           = array();
            $con['status'] = '1';
            $con['pid']    = $category_info['pid'];
            $category_list = $category_model->getCategoryTree($category_info['id']);
        } else {
            $con           = array();
            $con['status'] = '1';
            $con['pid']    = $category_info['pid'];
            $category_list = $category_model->getSameLevelCategoryTree($category_info['id']);
        }

        // ��ȡ���м��������
        $breadcrumb_list = $category_model->getParentCategory($category_info['id']);

        // ��ȡģ�嶩����Ϣ
        $theme_model = D('Site/Theme');
        $con         = array();
        $con['id']   = C('Site_config.theme');
        $theme_info  = $theme_model->where($con)->find();
        if (!$theme_info) {
            $this->error('�����ں�̨������վģ��');
        }

        // ģ���Զ�������
        $theme_info['config'] = json_decode($theme_info['config'], true);
        $this->assign('config_info', $theme_info['config']);

        $this->assign('category_info', $category_info);
        $this->assign('category_list', $category_list);
        $this->assign('breadcrumb_list', $breadcrumb_list);
        $this->assign('article_info', $category_info);
        $this->assign('meta_title', $category_info['title'] . "-" . $info['title']);

        // ��ʾҳ��
        if (C('CURRENT_THEME')) {
            // ����Ŀ¼
            $info['theme_path'] = __ROOT__ . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'];
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/footer.htm');
            $this->display('Site/theme/' . $theme_info['name'] . '/' . ($category_info['lists_template'] ?: 'lists_page'));
        } else {
            // ����Ŀ¼
            $info['theme_path'] = __ROOT__ . '/Application/Site/View/Site/theme/' . $theme_info['name'];
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/footer.htm');
            $this->display('Site/theme/' . $theme_info['name'] . '/' . ($category_info['lists_template'] ?: 'lists_page'));
        }
    }

    /**
     * �����б�
     * @author jry <7661660@qq.com>
     */
    public function detail($id)
    {
        // ��վ����
        $info = $this->info;

        // ��ȡ������Ϣ
        $article_model = D('Article');
        $con           = array();
        $con['status'] = '1';
        $con['id']     = $id;
        $article_info  = $article_model->where($con)->find();

        // �����Ķ�����
        $article_model->where($con)->setInc('view_count');

        // ��ȡ������Ϣ
        $category_model = D('Category');
        $con            = array();
        $con['status']  = '1';
        $con['id']      = $article_info['cid'];
        $category_info  = $category_model->where($con)->find();
        if (!$category_info) {
            $this->error('���಻����');
        }

        // ��ȡ�뵱ǰ����ͬ���ķ���
        if (!$category_info['pid']) {
            $con           = array();
            $con['status'] = '1';
            $con['pid']    = $category_info['pid'];
            $category_list = $category_model->getCategoryTree($category_info['id']);
        } else {
            $con           = array();
            $con['status'] = '1';
            $con['pid']    = $category_info['pid'];
            $category_list = $category_model->getSameLevelCategoryTree($category_info['id']);
        }

        // ��ȡ���м��������
        $breadcrumb_list = $category_model->getParentCategory($category_info['id']);

        // ��ȡģ�嶩����Ϣ
        $theme_model = D('Site/Theme');
        $con         = array();
        $con['id']   = C('Site_config.theme');
        $theme_info  = $theme_model->where($con)->find();
        if (!$theme_info) {
            $this->error('�����ں�̨������վģ��');
        }

        // ģ���Զ�������
        $theme_info['config'] = json_decode($theme_info['config'], true);
        $this->assign('config_info', $theme_info['config']);

        $this->assign('category_info', $category_info);
        $this->assign('category_list', $category_list);
        $this->assign('breadcrumb_list', $breadcrumb_list);
        $this->assign('article_info', $article_info);
        $this->assign('meta_title', $article_info['title'] . "-" . $info['title']);

        // ��ʾҳ��
        if (C('CURRENT_THEME')) {
            // ����Ŀ¼
            $info['theme_path'] = __ROOT__ . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'];
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/footer.htm');

            // ����ҳģ��
            if ($article_info['detail_template']) {
                $detail_tmp = $article_info['detail_template'];
            } else if ($category_info['detail_template']) {
                $detail_tmp = $category_info['detail_template'];
            } else {
                $detail_tmp = 'detail';
            }
            $this->display('Site/theme/' . $theme_info['name'] . '/' . $detail_tmp);
        } else {
            // ����Ŀ¼
            $info['theme_path'] = __ROOT__ . '/Application/Site/View/Site/theme/' . $theme_info['name'];
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/footer.htm');

            // ����ҳģ��
            if ($article_info['detail_template']) {
                $detail_tmp = $article_info['detail_template'];
            } else if ($category_info['detail_template']) {
                $detail_tmp = $category_info['detail_template'];
            } else {
                $detail_tmp = 'detail';
            }
            $this->display('Site/theme/' . $theme_info['name'] . '/' . $detail_tmp);
        }
    }

    /**
     * �����б�
     * @author jry <7661660@qq.com>
     */
    public function search($keyword)
    {
        // ��վ����
        $info = $this->info;

        // ��ȡ�����б�
        $article_model = D('Article');
        $con           = array();
        $con['status'] = '1';
        $con['cid']    = $cid;
        $con['title']  = array('like', $keyword);
        $p             = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $data_list     = $article_model->where($con)->page($p, 9)->select();

        // ��ҳ
        $page = new \lyf\Page(
            $article_model->where($con)->count(),
            9
        );

        // ��ȡģ�嶩����Ϣ
        $theme_model = D('Site/Theme');
        $con         = array();
        $con['id']   = C('Site_config.theme');
        $theme_info  = $theme_model->where($con)->find();
        if (!$theme_info) {
            $this->error('�����ں�̨������վģ��');
        }

        // ģ���Զ�������
        $theme_info['config'] = json_decode($theme_info['config'], true);
        $this->assign('config_info', $theme_info['config']);

        $this->assign('data_list', $data_list);
        $this->assign('page', $page->show());
        $this->assign('meta_title', $category_info['title'] . "-" . $info['title']);

        // ��ʾҳ��
        if (C('CURRENT_THEME')) {
            // ����Ŀ¼
            $info['theme_path'] = __ROOT__ . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'];
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/footer.htm');
            $this->display('Site/theme/' . $theme_info['name'] . '/search');
        } else {
            // ����Ŀ¼
            $info['theme_path'] = __ROOT__ . '/Application/Site/View/Site/theme/' . $theme_info['name'];
            $this->assign('info', $info);
            $this->assign('theme_path_header', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/header.htm');
            $this->assign('theme_path_footer', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/footer.htm');
            $this->display('Site/theme/' . $theme_info['name'] . '/search');
        }
    }

    /**
     * ����
     * @author jry <7661660@qq.com>
     */
    public function liuyan()
    {

        // ��������
        if (request()->isPost()) {
            $liuyan_model = D('Liuyan');
            $data         = $liuyan_model->create();
            if ($data) {
                $result = $liuyan_model->add($data);
                if ($result) {
                    $this->success('���Է���ɹ�');
                } else {
                    $this->error('���Է���ʧ�ܣ�' . $liuyan_model->getError());
                }
            } else {
                $this->error('���Է���ʧ�ܣ�' . $liuyan_model->getError());
            }
        }
    }

    /**
     * ��
     * @author jry <7661660@qq.com>
     */
    public function form($fid)
    {
        $form_model  = D('Site/form');
        $field_model = D('Site/field');

        // ��ȡģ�嶩����Ϣ
        $theme_model = D('Site/Theme');
        $con         = array();
        $con['id']   = C('Site_config.theme');
        $theme_info  = $theme_model->where($con)->find();
        if (!$theme_info) {
            $this->error('�����ں�̨������վģ��');
        }
        // �ύ��
        if (request()->isPost()) {
            if (!$_POST) {
                $this->error('����д���ݺ��ύ');
            }
            $map2           = array();
            $map2['fid']    = $fid;
            $map2['status'] = 1;
            $field_info     = $field_model->where($map2)->select(); //�˱����ֶ����
            foreach ($field_info as $v2) {
                $data[$v2['name']] = $_POST[$v2['name']];
                if (is_array($data[$v2['name']])) {
                    $data[$v2['name']] = implode('/', $data[$v2['name']]);
                }

                //����ȫ������
                if (!$data[$v2['name']]) {
                    $this->error('����д' . $v2['name']);
                }
            }
            $data        = json_encode($data); //ת����json��ʽ
            $data_model  = D('Site/Data');
            $add         = array();
            $add['data'] = $data;
            $add['fid']  = $fid; //��װд������
            $add         = $data_model->create($add);
            if ($add) {
                if ($data_model->add($add) !== false) {
                    $this->success('�ύ�ɹ�');
                } else {
                    $this->error('�ύʧ��');
                }
            } else {
                $this->error($data_model->getError());
            }
        } else {
            // ��վ����
            $info = $this->info;

            // ��
            $map           = array();
            $map['id']     = $fid;
            $map['status'] = 1;
            $form_info     = $form_model->where($map)->find(); //���������
            if (!$form_info) {
                $this->error('�˱���ͣ��');
            }
            $map2           = array();
            $map2['fid']    = $fid;
            $map2['status'] = 1;
            $field_info     = $field_model->where($map2)->select(); //�˱����ֶ����
            //�����ֶ�����
            foreach ($field_info as $k1 => &$v1) {
                if ($v1['type'] == 'radio' || $v1['type'] == 'checkbox' || $v1['type'] == 'select') {
                    $v1['choice'] = explode("/", $v1['choose']);
                }
            }
            $this->assign('form_info', $form_info);
            $this->assign('field_info', $field_info);

            // ��ʾҳ��
            if (C('CURRENT_THEME')) {
                // ����Ŀ¼
                $info['theme_path'] = __ROOT__ . '/Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'];
                $this->assign('info', $info);
                $this->assign('theme_path_header', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/header.htm');
                $this->assign('theme_path_footer', './Theme/' . C('CURRENT_THEME') . '/Site/Site/theme/' . $theme_info['name'] . '/footer.htm');
                $this->display('Site/theme/' . $theme_info['name'] . '/form');
            } else {
                // ����Ŀ¼
                $info['theme_path'] = __ROOT__ . '/Application/Site/View/Site/theme/' . $theme_info['name'];
                $this->assign('info', $info);
                $this->assign('theme_path_header', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/header.htm');
                $this->assign('theme_path_footer', './Application/Site/View/Site/theme/' . $theme_info['name'] . '/footer.htm');
                $this->display('Site/theme/' . $theme_info['name'] . '/form');
            }
        }
    }
}
