<div class="pull-right">
    @if ($paginator->hasPages())
    <div class="layui-box layui-laypage layui-laypage-page">
        <span class="layui-laypage-count">共 {{ $paginator->total() }} 条</span>
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <a href="javascript:;" class="layui-laypage-prev layui-disabled">上一页</a>
        @else
            <a href="javascript:;" class="layui-laypage-prev" lay-page="{{ $paginator->currentPage()-1 }}">上一页</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
            <span >{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                    <span class="layui-laypage-curr"><em class="layui-laypage-em" style="background-color:#1E9FFF;"></em><em>{{ $page }}</em></span>
                    @else
                       <a href="javascript:;" lay-page="{{ $page }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="javascript:;" class="layui-laypage-next" rel="next" lay-page="{{ $paginator->currentPage()+1 }}">下一页</a>
        @else
            <a href="javascript:;" class="layui-laypage-next layui-disabled">下一页</a>
        @endif
        <span class="layui-laypage-limits">
            @php $limit=$paginator->perPage(); @endphp
            <select lay-ignore="">
                <option value="10" @if($limit==10)selected="selected"@endif>10 条/页</option>
                <option value="15" @if($limit==15)selected="selected"@endif>15 条/页</option>
                <option value="20" @if($limit==20)selected="selected"@endif>20 条/页</option>
                <option value="50" @if($limit==50)selected="selected"@endif>50 条/页</option>
                <option value="100" @if($limit==50)selected="selected"@endif>100 条/页</option>
            </select>
        </span>
        <span class="layui-laypage-skip">到第 <input type="number" min="1" max="{{  $paginator->lastPage() }}" value="1" class="layui-input">页 <button type="button" class="layui-laypage-btn" style="color:#333;">确定</button> </span>
    </div>
    @endif
</div>

