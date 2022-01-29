<template>
  <div>
    <div class="row">
      <div class="col-12">
        <h2 class="top_title text-center">Bms Table</h2>
      </div>
      <div class="col-12 text-center">
        <div class="row">
          <div class="col"></div>
          <div class="col-3">
            <div class="card mb-4 box-shadow">
              <div class="card-header">
                <h4 class="my-0 font-weight-normal">発注日</h4>
              </div>
              <div class="card-body p-0 d-flex flex-column justify-content-between">
                <div>
                  <div class="form-group mb-0">
                    <input
                      type="text"
                      v-model="order_date"
                      name="order_date"
                      value
                      class="form-control text-center"
                      id="order_date"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-3">
            <div class="card mb-4 box-shadow">
              <div class="card-header">
                <h4 class="my-0 font-weight-normal">納品日</h4>
              </div>
              <div class="card-body p-0 d-flex flex-column justify-content-between">
                <div>
                  <div class="form-group mb-0">
                    <input
                      type="text"
                      v-model="expected_delivery_date"
                      name="delivery_date"
                      value
                      class="form-control text-center"
                      id="delivery_date"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-3">
            <div class="card mb-4 box-shadow">
              <div class="card-header">
                <h4 class="my-0 font-weight-normal">ステータス</h4>
              </div>
              <div class="card-body p-0 d-flex flex-column justify-content-between">
                <div>
                  <div class="form-group mb-0">
                    <input
                      type="text"
                      v-model="status"
                      name="delivery_date"
                      value
                      class="form-control text-center"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col"></div>
        </div>
      </div>
      <div class="col-12">
        <div class>
          <div class="row">
            <div class="col-6">
              <button class="btn btn-primary">ピッキング表 出力</button>
              <button class="btn btn-danger">すべて確定</button>
              <router-link :to="{name:'order_details_canvas',params:{byr_order_id:byr_order_id} }" class="btn btn-success">伝票出力</router-link>
              <button class="btn btn-info">確定送信</button>
            </div>
            <div class="col-6 text-right">
              <button class="btn btn-warning">受注データ 出力</button>
              <button class="btn btn-dark">確定データ 取込</button>
              <a href="index.html" class="btn btn-primary">発注データ一覧へ戻る</a>
            </div>
          </div>
          <table class="table table-striped table-bordered data_table">
            <thead>
              <tr>
                <th colspan="100%" style="border: none;">

                </th>
              </tr>
              <tr>
                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="id"
                  style="cursor: pointer"

                >
                  No
                  <span id="id_icon"></span>
                </th>
                <th>
                  <input type="checkbox" class="form-control check_all" />
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  送信者ＩＤ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	送信者ＩＤ発行元
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	受信者ＩＤ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	受信者ＩＤ発行元
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	標準名称
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	バージョン
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	インスタンスＩＤ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		メッセージ種
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	作成日時
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	タイプ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	テスト区分ＩＤ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	最終送信先ＩＤ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	メッセージ識別ＩＤ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	送信者ステーションアドレス
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		最終受信者ステーションアドレス
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	直接受信者ステーションアドレス
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		取引数
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	システム情報キー
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	システム情報値
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	バージョン番号
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	バージョン番号
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		名前空間
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		バージョン
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	支払法人コード
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		支払法人GLN
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	支払法人名称
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	支払法人名称カナ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	発注者コード
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	発注者GLN
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		発注者名称
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	発注者名称カナ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	取引番号（発注・返品）
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		取引付属番号
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		直接納品先
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		コード
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			直接納品先GLN
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		直接納品先名称
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		直接納品先名称カナ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		最終納品先コード
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		最終納品先GLN
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		最終納品先名称
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		最終納品先名称カナ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		計上部署コード
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		計上部署GLN
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			計上部署名称
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		計上部署名称（カナ）
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		陳列場所コード
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			陳列場所名称
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			陳列場所名称カナ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		請求取引先コード
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		請求取引先GLN
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		請求取引先名
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		請求取引先名カナ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		取引先コード
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		取引先GLN
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			取引先名称
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			取引先名称カナ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		枝番
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			出荷先コード
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		出荷場所GLN
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			納品経路
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  	便No
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		通過在庫区分
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			納品区分
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		指定納品時刻
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			輸送手段
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			バーコード情報
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			カテゴリー名称1（印字用）
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			カテゴリー名称2（印字用）
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			最終納品先略称（印字用）
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			ラベル自由使用欄（印字用）
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			ラベル自由使用欄半角カナ（印字用）
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			商品分類（大）
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		商品分類（中）
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			発注日
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			直接納品先納品日
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			最終納品先納品日
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		計上日
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		販促開始日
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			販促終了日
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		取引（発注・返品）データ有効日
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			商品区分
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		発注区分
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		出荷データ有無区分
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		PB区分
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		温度区分
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			酒区分
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			処理種別
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			伝票レス区分
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		取引番号区分
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		パック区分
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			不定貫区分
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			税区分
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			税率
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		自由使用欄
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		自由使用欄半角カナ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		原価金額合計
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			売価金額合計
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			税額合計金額
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			数量合計
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		発注単位数量合計
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			重量合計
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		取引明細番号（発注・返品）
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		取引付属明細番号
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		元取引番号
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			元取引明細番号
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			商品分類（小）
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		商品分類（細）
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			配達予定日
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		納品期限
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			センター納品詳細指示
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		メーカーコード
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			商品コード（ＧTIN）
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		商品コード（発注用）
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			商品コード区分
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			商品コード（取引先）
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			商品名
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		商品名カナ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		規格
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		規格カナ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		カラーコード
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			カラー名称
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		カラー名称カナ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		サイズコード
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		サイズ名称
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			サイズ名称カナ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			入数
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		都道府県コード
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			国コード
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			産地名
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		水域コード
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			水域名
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			原産エリア
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			等級
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			階級
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			銘柄
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		商品ＰＲ
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		バイオ区分
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		品種コード
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		養殖区分
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		解凍区分
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  		商品状態区分
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			形状・部位
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			用途
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			法定管理義務商材区分
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			原価金額
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			原単価
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			売価金額
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			売単価
                  <span id="name_icon"></span>
                </th>

                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="name"
                  style="cursor: pointer;min-width:100px;">
                  			税額
                  <span id="name_icon"></span>
                </th>
                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="email"
                  style="cursor: pointer;min-width:100px;">
                 	発注単位
                  <span id="total_icon"></span>
                </th>
                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="email"
                  style="cursor: pointer;min-width:100px;">
                 		発注単位コード
                  <span id="total_icon"></span>
                </th>
                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="email"
                  style="cursor: pointer;min-width:100px;">
                 			発注荷姿コード
                  <span id="total_icon"></span>
                </th>
                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="email"
                  style="cursor: pointer;min-width:100px;">
                 			発注数量（バラ）
                  <span id="total_icon"></span>
                </th>
                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="email"
                  style="cursor: pointer;min-width:100px;">
                 		発注数量（発注単位数）
                  <span id="total_icon"></span>
                </th>
                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="email"
                  style="cursor: pointer;min-width:100px;">
                 			取引単位重量
                  <span id="total_icon"></span>
                </th>
                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="email"
                  style="cursor: pointer;min-width:100px;">
                 			単価登録単位
                  <span id="total_icon"></span>
                </th>
                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="email"
                  style="cursor: pointer;min-width:100px;">
                 			商品重量
                  <span id="total_icon"></span>
                </th>
                <th
                  class="sorting"
                  data-sorting_type="asc"
                  data-column_name="email"
                  style="cursor: pointer;min-width:100px;">
                 		発注重量
                  <span id="total_icon"></span>
                </th>
                 <th style="min-width:100px">数量確定</th>
                <th style="min-width:100px">発注データ修正</th>
                </tr>
            </thead>
            <tbody>
              <tr
                v-for="(order_detail_list,index) in order_detail_lists"
                :key="order_detail_list.byr_order_detail_id"
              >
                <td>{{index+1}}</td>
                <td>
                  <input type="checkbox" class="form-control check_item" />
                </td>

                <td>{{order_detail_list.sta_sen_identifier}}</td>
                <td>{{order_detail_list.sta_sen_ide_authority}}</td>
                <td>{{order_detail_list.sta_rec_identifier}}</td>
                <td>{{order_detail_list.sta_rec_ide_authority}}</td>
                <td>{{order_detail_list.sta_doc_standard}}</td>
                <td>{{order_detail_list.sta_doc_typeVersion}}</td>
                <td>{{order_detail_list.sta_doc_instanceIdentifier}}</td>
                <td>{{order_detail_list.sta_doc_type}}</td>
                <td>{{order_detail_list.sta_doc_creationDateAndTime}}</td>
                <td>{{order_detail_list.sta_bus_scope_type}}</td>
                <td>{{order_detail_list.sta_bus_scope_instanceIdentifier}}</td>
                <td>{{order_detail_list.sta_bus_scope_identifier}}</td>
                <td>{{order_detail_list.mes_ent_uniqueCreatorIdentification}}</td>
                <td>{{order_detail_list.mes_mes_senderStationAddress}}</td>
                <td>{{order_detail_list.mes_mes_ultimateReceiverStationAddress}}</td>
                <td>{{order_detail_list.mes_mes_immediateReceiverStationAddres}}</td>
                <td>{{order_detail_list.mes_mes_numberOfTradingDocuments}}</td>
                <td>{{order_detail_list.mes_mes_sys_key}}</td>
                <td>{{order_detail_list.mes_mes_sys_value}}</td>
                <td>{{order_detail_list.mes_lis_con_version}}</td>
                <td>{{order_detail_list.mes_lis_doc_version}}</td>
                <td>{{order_detail_list.mes_lis_ext_namespace}}</td>
                <td>{{order_detail_list.mes_lis_ext_version}}</td>
                <td>{{order_detail_list.mes_lis_pay_code}}</td>
                <td>{{order_detail_list.mes_lis_pay_gln}}</td>
                <td>{{order_detail_list.mes_lis_pay_name}}</td>
                <td>{{order_detail_list.mes_lis_pay_name_sbcs}}</td>
                <td>{{order_detail_list.mes_lis_buy_code}}</td>
                <td>{{order_detail_list.mes_lis_buy_gln}}</td>
                <td>{{order_detail_list.mes_lis_buy_name}}</td>
                <td>{{order_detail_list.mes_lis_buy_name_sbcs}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_tradeNumber}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_additionalTradeNumber}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_shi_code}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_shi_gln}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_shi_name}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_shi_name_sbcs}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_rec_code}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_rec_gln}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_rec_name}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_rec_name_sbcs}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_tra_code}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_tra_gln}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_tra_name}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_tra_name_sbcs}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_dis_code}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_dis_name}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_dis_name_sbcs}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_pay_code}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_pay_gln}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_pay_name}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_pay_name_sbcs}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_sel_code}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_sel_gln}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_sel_name}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_sel_name_sbcs}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_sel_branchNumber}}</td>
                <td>{{order_detail_list.mes_lis_ord_par_sel_shipLocationCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_log_shi_gln}}</td>
                <td>{{order_detail_list.mes_lis_ord_log_del_routeCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_log_del_deliveryServiceCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_log_del_stockTransferCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_log_del_deliveryCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_log_del_deliveryTime}}</td>
                <td>{{order_detail_list.mes_lis_ord_log_del_transportationCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_log_log_barcodePrint}}</td>
                <td>{{order_detail_list.mes_lis_ord_log_log_categoryNamePrint1}}</td>
                <td>{{order_detail_list.mes_lis_ord_log_log_categoryNamePrint2}}</td>
                <td>{{order_detail_list.mes_lis_ord_log_log_receiverAbbrName}}</td>
                <td>{{order_detail_list.mes_lis_ord_log_log_text}}</td>
                <td>{{order_detail_list.mes_lis_ord_log_log_text_sbcs}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_goo_majorCategory}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_goo_subMajorCategory}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_dat_orderDate}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_dat_deliveryDate}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_dat_deliveryDateToReceiver}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_dat_transferOfOwnershipDate}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_dat_campaignStartDate}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_dat_campaignEndDate}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_dat_validUntilDate}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_ins_goodsClassificationCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_ins_orderClassificationCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_ins_shipNotificationRequestCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_ins_privateBrandCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_ins_temperatureCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_ins_liquorCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_ins_tradeTypeCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_ins_paperFormLessCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_fre_tradeNumberRequestCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_fre_packageCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_fre_variableMeasureItemCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_tax_taxTypeCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_tax_taxRate}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_not_text}}</td>
                <td>{{order_detail_list.mes_lis_ord_tra_not_text_sbcs}}</td>
                <td>{{order_detail_list.mes_lis_ord_tot_tot_netPriceTotal}}</td>
                <td>{{order_detail_list.mes_lis_ord_tot_tot_sellingPriceTotal}}</td>
                <td>{{order_detail_list.mes_lis_ord_tot_tot_taxTotal}}</td>
                <td>{{order_detail_list.mes_lis_ord_tot_tot_itemTotal}}</td>
                <td>{{order_detail_list.mes_lis_ord_tot_tot_unitTotal}}</td>
                <td>{{order_detail_list.mes_lis_ord_tot_fre_unitWeightTotal}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_lin_lineNumber}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_lin_additionalLineNumber}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_tradeNumber}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_lineNumber}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_goo_minorCategory}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_goo_detailedCategory}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_ite_scheduledDate}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_ite_deadlineDate}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_ite_centerDeliveryInstructionCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_ite_makerCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_ite_gtin}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_ite_orderItemCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_ite_codeType}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_ite_supplierItemCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_ite_name}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_ite_name_sbcs}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_ite_ite_spec}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_ite_ite_spec_sbcs}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_ite_col_colorCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_ite_col_description}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_ite_col_description_sbcs}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_ite_siz_sizeCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_ite_siz_description}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_ite_siz_description_sbcs}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_packingQuantity}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_prefectureCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_countryCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_fieldName}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_waterAreaCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_waterAreaName}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_areaOfOrigin}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_itemGrade}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_itemClass}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_brand}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_itemPR}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_bioCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_breedCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_cultivationCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_defrostCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_itemPreservationCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_itemShapeCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_use}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_sta_statutoryClassificationCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_amo_itemNetPrice}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_amo_itemNetPrice_unitPrice}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_amo_itemSellingPrice}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_amo_itemSellingPrice_unitPrice}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_amo_itemTax}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_qua_unitMultiple}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_qua_unitOfMeasure}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_qua_packageIndicator}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_qua_ord_quantity}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_qua_ord_numOfOrderUnits}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_unitWeight}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_unitWeightCode}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_itemWeight}}</td>
                <td>{{order_detail_list.mes_lis_ord_lin_fre_orderWeight}}</td>
                <td>
                  <button @click="update_shipment_detail(order_detail_list)" class="btn btn-primary">確定</button>
                </td>

                <td>
                  <button
                    @click="edit_order_detail(order_detail_list)"
                    class="btn btn-success"
                  >修正</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <b-modal
      size="lg"
      :hide-backdrop="true"
      title="発注データ修正"
      ok-title="修正"
      cancel-title="キャンセル"
      @ok.prevent="save_user()"
      v-model="edit_order_modal"
      :no-enforce-focus="true">
      <div class="panel-body add_item_body">
        <form>
          <input type="hidden" name="vendor_item_id" id="vendor_item_id" value />
          <div class="row">
            <div class="col-6">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon3">伝票番号</span>
                </div>
                <input
                  type="text"
                  class="form-control"
                  id="basic-url"
                  aria-describedby="basic-addon3"
                />
              </div>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon3">発注日</span>
                </div>
                <input
                  type="text"
                  class="form-control"
                  id="basic-url"
                  aria-describedby="basic-addon3"
                />
              </div>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon3">商品名</span>
                </div>
                <input
                  type="text"
                  class="form-control"
                  id="basic-url"
                  aria-describedby="basic-addon3"
                />
              </div>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon3">原価</span>
                </div>
                <input
                  type="text"
                  class="form-control"
                  id="basic-url"
                  aria-describedby="basic-addon3"
                />
              </div>
            </div>
            <div class="col-6">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon3">JAN</span>
                </div>
                <input
                  type="text"
                  class="form-control"
                  id="basic-url"
                  aria-describedby="basic-addon3"
                />
              </div>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon3">納品日</span>
                </div>
                <input
                  type="text"
                  class="form-control"
                  id="basic-url"
                  aria-describedby="basic-addon3"
                />
              </div>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon3">規格</span>
                </div>
                <input
                  type="text"
                  class="form-control"
                  id="basic-url"
                  aria-describedby="basic-addon3"
                />
              </div>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon3">売価</span>
                </div>
                <input
                  type="text"
                  class="form-control"
                  id="basic-url"
                  aria-describedby="basic-addon3"
                />
              </div>
            </div>
          </div>
        </form>
      </div>
      <!-- </div>
        </div>
      </div>-->
    </b-modal>
  </div>
</template>
<script>
export default {
  data() {
    return {
      sortKey: '',
      reverse:true,
      order_by:'asc',
      order_detail_lists: {},
      order_date: "",
      order_detail_list: [],
      show_hide_col_list: [],
      expected_delivery_date: "",
      status: "",
      byr_order_id: "",
      edit_order_modal: false,
      form: new Form({})
    };
  },
  methods: {

    exec_confirm_qty(order_detail,event){
      if(parseFloat(order_detail.confirm_quantity)>parseFloat(order_detail.order_quantity)){
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Confirm Quantity!',
            text: 'You can not confrim order more then your order quantity!'
        });
                order_detail.confirm_quantity=order_detail.order_quantity
      }
      if(event.key=='Enter'){
        event.preventDefault()

        // event.target.nextElementSibling.focus()
        // console.log(event.target.parent.closest('.lack_reasons'));
      }
    },
    sortBynumeric_valu(sortKey){

      // this.order_detail_lists.sort((a, b) => a[sortKey] < b[sortKey] ? 1 : -1);
      if(this.order_by=='asc'){
        this.order_by='desc';
        this.order_detail_lists.sort((a, b) => a[sortKey]-b[sortKey]);
      }else{
         this.order_by='asc';
        this.order_detail_lists.sort((a, b) => b[sortKey]-a[sortKey]);
      }

    },
    sortByja_valu(sortKey){
      if(this.order_by=='asc'){
        this.order_by='desc';
        this.order_detail_lists.sort( (a, b) => a[sortKey].localeCompare(b[sortKey], 'ja', {ignorePunctuation: true}));
      }else{
         this.order_by='asc';
        this.order_detail_lists.sort((a, b) =>  b[sortKey].localeCompare(a[sortKey], 'ja', {ignorePunctuation: true}));
      }
    },
    update_shipment_detail(order_detail){

      axios({method: 'POST',
    url: this.BASE_URL + "api/update_shipment_detail",
    data: order_detail
    })
    .then(({data})=> {
       Fire.$emit('LoadByrorderDetail');
    })
    .catch(function (response) {
        //handle error

    });
    },
    //get Table data
    get_all_byr_order_detail() {
      axios
        .get(this.BASE_URL + "api/get_data_order_byr_order_id/" + this.byr_order_id)
        .then(({data}) => {
          this.order_detail_lists = data.order_list_detail;
          this.show_hide_col_list = data.slected_list;
        });
    },

    col_show_hide_setting(url_slug) {
    //   console.log(this.show_hide_col_list.length + "col lenght");
      if (this.show_hide_col_list.length == 0) {
        var post_data = {
          url_slug: url_slug,
          user_id: Globals.user_info_id
        };
        axios.post(this.BASE_URL + "api/tblecolsetting", post_data)
          .then(({data}) => {
          });
      }
    },
    edit_order_detail(order_detail_list) {
      this.edit_order_modal = true;
    }
  },

  created() {
    this.byr_order_id = this.$route.params.byr_order_id;
    this.get_all_byr_order_detail();
    Fire.$on("LoadByrorderDetail", () => {
      this.get_all_byr_order_detail();
    });
    this.col_show_hide_setting(this.$route.name);
  },
  mounted() {
  }
};
</script>
