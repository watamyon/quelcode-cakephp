<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event; // added.
use Exception; // added.


class AuctionController extends AuctionBaseController
{
	// デフォルトテーブルを使わない
	public $useTable = false;

	// 初期化処理
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Paginator');
		// 必要なモデルをすべてロード
		$this->loadModel('Users');
		$this->loadModel('Biditems');
		$this->loadModel('Bidrequests');
		$this->loadModel('Bidinfo');
		$this->loadModel('Bidmessages');
		// shippingのモデルを追加
		$this->loadModel('Shipping');
		// ログインしているユーザー情報をauthuserに設定
		$this->set('authuser', $this->Auth->user());
		// レイアウトをauctionに変更
		$this->viewBuilder()->setLayout('auction');
	}

	// トップページ
	public function index()
	{
		// ページネーションでBiditemsを取得
		$auction = $this->paginate('Biditems', [
			'order' => ['endtime' => 'desc'],
			'limit' => 10
		]);
		$this->set(compact('auction'));
	}

	// 商品情報の表示
	public function view($id = null)
	{
		// $idのBiditemを取得
		$biditem = $this->Biditems->get($id, [
			'contain' => ['Users', 'Bidinfo', 'Bidinfo.Users']
		]);
		// オークション終了時の処理
		if ($biditem->endtime < new \DateTime('now') and $biditem->finished == 0) {
			// finishedを1に変更して保存
			$biditem->finished = 1;
			$this->Biditems->save($biditem);
			// Bidinfoを作成する
			$bidinfo = $this->Bidinfo->newEntity();
			// Bidinfoのbiditem_idに$idを設定
			$bidinfo->biditem_id = $id;
			// 最高金額のBidrequestを検索
			$bidrequest = $this->Bidrequests->find('all', [
				'conditions' => ['biditem_id' => $id],
				'contain' => ['Users'],
				'order' => ['price' => 'desc']
			])->first();
			// Bidrequestが得られた時の処理
			if (!empty($bidrequest)) {
				// Bidinfoの各種プロパティを設定して保存する
				$bidinfo->user_id = $bidrequest->user->id;
				$bidinfo->user = $bidrequest->user;
				$bidinfo->price = $bidrequest->price;
				$this->Bidinfo->save($bidinfo);
			}
			// Biditemのbidinfoに$bidinfoを設定
			$biditem->bidinfo = $bidinfo;
		}
		// Bidrequestsからbiditem_idが$idのものを取得
		$bidrequests = $this->Bidrequests->find('all', [
			'conditions' => ['biditem_id' => $id],
			'contain' => ['Users'],
			'order' => ['price' => 'desc']
		])->toArray();
		// オブジェクト類をテンプレート用に設定
		$this->set(compact('biditem', 'bidrequests'));
	}

	// 出品する処理
	public function add()
	{
		// Biditemインスタンスを用意
		$biditem = $this->Biditems->newEntity();
		// POST送信時の処理
		if ($this->request->is('post')) {
			// 画像ファイルの中身の取得
			$file = $_FILES['file_name'];
			// $biditemにフォームの送信内容を反映
			$biditem = $this->Biditems->patchEntity($biditem, $this->request->getData());
			$biditem->file_name = $file['name'];
			// $biditemを保存する
			if ($this->Biditems->save($biditem)) {
				// ファイル名を一意にする為にIDを付け加えるファイル名更新処理
				$pathin = pathinfo($file['name']);
				$file_ext = $pathin['extension'];
				$biditem->file_name = $biditem['id'] . '.' . $file_ext;
				if ($this->Biditems->save($biditem)) {
					// 画像ファイルを指定のフォルダに保存（フォルダは自作）
					$filePath = '/var/www/html/mycakeapp/webroot/img/auction/' . $biditem['id'] . '.' . $file_ext;
					$success = move_uploaded_file($file['tmp_name'], $filePath);
					// 成功時のメッセージ
					$this->Flash->success(__('保存しました。'));
					// トップページ（index）に移動
					return $this->redirect(['action' => 'index']);
				}
			}
			// 失敗時のメッセージ
			$this->Flash->error(__('保存に失敗しました。もう一度入力下さい。'));
		}
		// 値を保管
		$this->set(compact('biditem'));
	}

	// 入札の処理
	public function bid($biditem_id = null)
	{
		// 入札用のBidrequestインスタンスを用意
		$bidrequest = $this->Bidrequests->newEntity();
		// $bidrequestにbiditem_idとuser_idを設定
		$bidrequest->biditem_id = $biditem_id;
		$bidrequest->user_id = $this->Auth->user('id');
		// POST送信時の処理
		if ($this->request->is('post')) {
			// $bidrequestに送信フォームの内容を反映する
			$bidrequest = $this->Bidrequests->patchEntity($bidrequest, $this->request->getData());
			// Bidrequestを保存
			if ($this->Bidrequests->save($bidrequest)) {
				// 成功時のメッセージ
				$this->Flash->success(__('入札を送信しました。'));
				// トップページにリダイレクト
				return $this->redirect(['action' => 'view', $biditem_id]);
			}
			// 失敗時のメッセージ
			$this->Flash->error(__('入札に失敗しました。もう一度入力下さい。'));
		}
		// $biditem_idの$biditemを取得する
		$biditem = $this->Biditems->get($biditem_id);
		$this->set(compact('bidrequest', 'biditem'));
	}

	// 落札者とのメッセージ
	public function msg($bidinfo_id = null)
	{
		// Bidmessageを新たに用意
		$bidmsg = $this->Bidmessages->newEntity();
		// POST送信時の処理
		if ($this->request->is('post')) {
			// 送信されたフォームで$bidmsgを更新
			$bidmsg = $this->Bidmessages->patchEntity($bidmsg, $this->request->getData());
			// Bidmessageを保存
			if ($this->Bidmessages->save($bidmsg)) {
				$this->Flash->success(__('保存しました。'));
			} else {
				$this->Flash->error(__('保存に失敗しました。もう一度入力下さい。'));
			}
		}
		try { // $bidinfo_idからBidinfoを取得する
			$bidinfo = $this->Bidinfo->get($bidinfo_id, ['contain' => ['Biditems']]);
		} catch (Exception $e) {
			$bidinfo = null;
		}
		// Bidmessageをbidinfo_idとuser_idで検索
		$bidmsgs = $this->Bidmessages->find('all', [
			'conditions' => ['bidinfo_id' => $bidinfo_id],
			'contain' => ['Users'],
			'order' => ['created' => 'desc']
		]);
		$this->set(compact('bidmsgs', 'bidinfo', 'bidmsg'));
	}

	// 落札情報の表示
	public function home()
	{
		// 自分が落札したBidinfoをページネーションで取得
		$bidinfo = $this->paginate('Bidinfo', [
			'conditions' => ['Bidinfo.user_id' => $this->Auth->user('id')],
			'contain' => ['Users', 'Biditems'],
			'order' => ['created' => 'desc'],
			'limit' => 10
		])->toArray();
		$this->set(compact('bidinfo'));
	}

	// 出品情報の表示
	public function home2()
	{
		// 自分が出品したBiditemをページネーションで取得
		$biditems = $this->paginate('Biditems', [
			'conditions' => ['Biditems.user_id' => $this->Auth->user('id')],
			'contain' => ['Users', 'Bidinfo'],
			'order' => ['created' => 'desc'],
			'limit' => 10
		])->toArray();
		$this->set(compact('biditems'));
	}

	//  発送先詳細・発送連絡の処理 落札者・出品者のみのアクセス制御
	public function ship($item_id = null)
	{
		$bidinfo = $this->Bidinfo->find()->where(['Bidinfo.biditem_id' => $item_id])->enableHydration(false)->toArray()[0];
		$biditem = $this->Biditems->find()->where(['Biditems.id' => $item_id])->enableHydration(false)->toArray()[0];
		$shipping = null;

		if ([] !== $this->Shipping->find()->where(['Shipping.item_id' => $item_id])->enableHydration(false)->toArray()) {
			$shipping_to = $this->Shipping->find()->where(['Shipping.item_id' => $item_id])->enableHydration(false)->toArray()[0];
		} else {
			$shipping_to = null;
		}
		// 落札者・出品者・ログインユーザーのidを変数に入れる
		$bidder_id = $bidinfo['user_id'];
		$seller_id = $biditem['user_id'];
		$login_userid = $this->Auth->user('id');
		// 落札者からのpost送信があった場合
		if ($bidder_id === $login_userid) {
			$shipping = $this->Shipping->newEntity();
			// ship.ctpから発送先の情報が送信されていれば、その発送先の情報をインスタンスに代入する
			if (null === $shipping_to) {
				$not_shipped_yet = ($shipping_to['is_shipped'] === false);
				if ($this->request->is('post')) {
					$is_shipped_false = 0;
					$is_received_false = 0;
					$post_data = $this->request->getData();
					$post_data['item_id'] = $item_id;
					$post_data['is_shipped'] = $is_shipped_false;
					$post_data['is_received'] = $is_received_false;
					$shipping = $this->Shipping->patchEntity($shipping, $post_data);
					// Shippingを保存
					if ($this->Shipping->save($shipping)) {
						return $this->redirect(['action' => 'ship', $item_id]);
					} else {
						$this->Flash->error(__('保存に失敗しました。もう一度入力下さい。'));
					}
				} elseif ($not_shipped_yet) {
					// 発送先詳細を送信済みで、まだ出品者からの発送通知が来ていない場合に、落札者にアクセス権限を与えるためコード
				}
			} elseif ($shipping_to['is_shipped'] === true) {
				return $this->redirect(['action' => 'receive', $item_id]);
			}
		} elseif ($seller_id === $login_userid) {
			if (isset($shipping_to)) {
				if ($this->request->is('post')) {
					$shipping = $this->Shipping->find()->where(['Shipping.item_id' => $item_id])->first();
					$shipping = $this->Shipping->patchEntity($shipping, ['is_shipped' => 1]);
					// Shippingを更新
					if ($this->Shipping->save($shipping)) {
						return $this->redirect(['action' => 'ship', $item_id]);
					} else {
						$this->Flash->error(__('保存に失敗しました。もう一度入力下さい。'));
					}
				}
			}
		} else {
			return $this->redirect(['action' => 'index']);
		}
		// viewに値を渡せるように保存。 同じ値が取れるものは排除する必要あり？e.g. bidinfo['biditem_id']でitem_idは取れる。
		$this->set(compact('bidinfo', 'item_id', 'biditem', 'bidder_id', 'seller_id', 'login_userid', 'shipping_to',  'shipping'));
	}

	public function receive($item_id = null)
	{
		$bidinfo = $this->Bidinfo->find()->where(['Bidinfo.biditem_id' => $item_id])->enableHydration(false)->toArray()[0];
		$biditem = $this->Biditems->find()->where(['Biditems.id' => $item_id])->enableHydration(false)->toArray()[0];
		$shipping = null;

		if ([] !== $this->Shipping->find()->where(['Shipping.item_id' => $item_id])->enableHydration(false)->toArray()) {
			$shipping_to = $this->Shipping->find()->where(['Shipping.item_id' => $item_id])->enableHydration(false)->toArray()[0];
		} else {
			$shipping_to = null;
		}
		// 落札者・ログインユーザーのidを変数に入れる
		$bidder_id = $bidinfo['user_id'];
		$login_userid = $this->Auth->user('id');
		if ($bidder_id === $login_userid && $shipping_to['is_shipped'] === true) {
			if ($this->request->is('post')) {
				$shipping = $this->Shipping->find()->where(['Shipping.item_id' => $item_id])->first();
				$shipping = $this->Shipping->patchEntity($shipping, ['is_received' => 1]);
				// Shippingを更新
				if ($this->Shipping->save($shipping)) {
					return $this->redirect(['action' => 'receive', $item_id]);
				} else {
					$this->Flash->error(__('保存に失敗しました。もう一度入力下さい。'));
				}
			}
		} else {
			return $this->redirect(['action' => 'index']);
		}
		$this->set(compact('bidinfo', 'item_id', 'biditem', 'bidder_id', 'login_userid', 'shipping', 'shipping_to'));
	}
}